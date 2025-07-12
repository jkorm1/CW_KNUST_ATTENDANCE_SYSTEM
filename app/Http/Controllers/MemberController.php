<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Cell;
use App\Models\Fold;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Base query with eager loading
        $query = Member::with(['cell', 'fold'])->orderBy('name');

        // Filter members based on user's role and leadership
        if ($user->isAdmin()) {
            $members = $query->paginate(20);
        } elseif ($user->isCellLeader()) {
            $cell = $user->getLedCell();
            $members = $cell ? $query->where('cell_id', $cell->id)->paginate(20) : new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        } elseif ($user->isFoldLeader()) {
            $fold = $user->getLedFold();
            $members = $fold ? $query->where('fold_id', $fold->id)->paginate(20) : new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        } else {
            $members = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }
        
        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cells = Cell::orderBy('name')->get();
        $folds = Fold::orderBy('name')->get();
        return view('members.create', compact('cells', 'folds'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|string',
            'invited_by' => 'nullable|exists:members,id',
            'first_visit_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'cell_id' => 'nullable|exists:cells,id',
            'fold_id' => 'nullable|exists:folds,id',
            'cell_leader_of' => 'nullable|sometimes|exists:cells,id',
            'assistant_cell_leader_of' => 'nullable|sometimes|exists:cells,id',
            'fold_leader_of' => 'nullable|sometimes|exists:folds,id',
            'assistant_fold_leader_of' => 'nullable|sometimes|exists:folds,id',
        ]);

        try {
            DB::beginTransaction();

            // If first timer and invited_by is set, assign cell and fold from inviter
            if ($data['status'] === 'first_timer' && !empty($data['invited_by'])) {
                $inviter = Member::find($data['invited_by']);
                if ($inviter) {
                    $data['cell_id'] = $inviter->cell_id;
                    $data['fold_id'] = $inviter->fold_id;
                }
            }

            // Create the member
            $member = Member::create($data);

            // Handle leadership assignments
            $this->handleLeadershipAssignment($member, $request);

            DB::commit();
            
            // Check if leader credentials were created
            if (session()->has('leader_credentials')) {
                $credentials = session('leader_credentials');
                session()->forget('leader_credentials');
                return redirect()->route('members.index')->with('success', 
                    "Member added successfully! Login credentials created for {$credentials['name']}: Email: {$credentials['email']}, Password: {$credentials['password']}");
            }
            
            return redirect()->route('members.index')->with('success', 'Member added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating member: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        $cells = Cell::orderBy('name')->get();
        $folds = Fold::orderBy('name')->get();
        return view('members.edit', compact('member', 'cells', 'folds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|string',
            'invited_by' => 'nullable|exists:members,id',
            'first_visit_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'cell_id' => 'nullable|exists:cells,id',
            'fold_id' => 'nullable|exists:folds,id',
            'cell_leader_of' => 'nullable|sometimes|exists:cells,id',
            'assistant_cell_leader_of' => 'nullable|sometimes|exists:cells,id',
            'fold_leader_of' => 'nullable|sometimes|exists:folds,id',
            'assistant_fold_leader_of' => 'nullable|sometimes|exists:folds,id',
        ]);

        try {
            DB::beginTransaction();

            // If first timer and invited_by is set, assign cell and fold from inviter
            if ($data['status'] === 'first_timer' && !empty($data['invited_by'])) {
                $inviter = Member::find($data['invited_by']);
                if ($inviter) {
                    $data['cell_id'] = $inviter->cell_id;
                    $data['fold_id'] = $inviter->fold_id;
                }
            }

            // Update the member
            $member->update($data);

            // Handle leadership assignments
            $this->handleLeadershipAssignment($member, $request);

            DB::commit();
            
            // Check if leader credentials were created
            if (session()->has('leader_credentials')) {
                $credentials = session('leader_credentials');
                session()->forget('leader_credentials');
                return redirect()->route('members.index')->with('success', 
                    "Member updated successfully! Login credentials created for {$credentials['name']}: Email: {$credentials['email']}, Password: {$credentials['password']}");
            }
            
            return redirect()->route('members.index')->with('success', 'Member updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating member: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Member deleted successfully.');
    }

    /**
     * Handle leadership assignment for a member
     */
    private function handleLeadershipAssignment(Member $member, Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return;
        }

        // If assigning as cell leader or assistant, remove from any fold
        if ($request->filled('cell_leader_of') || $request->filled('assistant_cell_leader_of')) {
            $member->fold_id = null;
            $member->save();
        }

        $this->assignLeadership('cell_leader_of', Cell::class, 'cell_leader_id', $member, $request);
        $this->assignLeadership('assistant_cell_leader_of', Cell::class, 'assistant_leader_id', $member, $request);
        $this->assignLeadership('fold_leader_of', Fold::class, 'fold_leader_id', $member, $request);
        $this->assignLeadership('assistant_fold_leader_of', Fold::class, 'assistant_leader_id', $member, $request);
    }

    private function assignLeadership($requestKey, $modelClass, $leaderColumn, Member $member, Request $request)
    {
        $newLeaderOfId = $request->input($requestKey);

        // Find the model that this member is currently a leader of for this role
        $currentlyLeads = $modelClass::where($leaderColumn, $member->id)->first();

        // If the member is currently a leader of something in this role
        if ($currentlyLeads) {
            // If the new assignment is different, or is 'none', nullify the old leadership role
            if ($currentlyLeads->id != $newLeaderOfId) {
                $currentlyLeads->update([$leaderColumn => null]);
            }
        }

        // If a new assignment is made (and it's not 'none')
        if ($newLeaderOfId) {
            $modelToLead = $modelClass::find($newLeaderOfId);
            if ($modelToLead) {
                // Nullify whoever is the current leader of the new assignment
                if ($modelToLead->$leaderColumn) {
                    // No need to remove user, just the role
                }
                $modelToLead->update([$leaderColumn => $member->id]);
                // If assigning as fold leader, ensure member is in the correct cell and fold
                if ($modelClass === \App\Models\Fold::class) {
                    $member->cell_id = $modelToLead->cell_id;
                    $member->fold_id = $modelToLead->id;
                    $member->save();
                }
                $this->createUserForLeader($member, 'usher');
            }
        }
    }

    /**
     * Create a user account for a leader
     */
    private function createUserForLeader(Member $member, $role = 'usher')
    {
        // Check if user already exists
        $existingUser = User::where('email', $member->email ?? $member->name . '@church.com')->first();
        
        if (!$existingUser) {
            $email = $member->email ?? $member->name . '@church.com';
            $password = 'password123'; // Default password
            
            $user = User::create([
                'name' => $member->name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            $user->assignRole($role);
            
            // Store success message with login credentials
            session()->flash('leader_credentials', [
                'name' => $member->name,
                'email' => $email,
                'password' => $password,
                'role' => $role
            ]);
        }
    }
}
