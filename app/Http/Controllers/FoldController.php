<?php

namespace App\Http\Controllers;

use App\Models\Fold;
use App\Models\Cell;
use Illuminate\Http\Request;

class FoldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $folds = Fold::with('cell')->orderBy('name')->paginate(20);
        } elseif ($user->isCellLeader()) {
            $cell = $user->getLedCell();
            $folds = $cell ? $cell->folds()->orderBy('name')->paginate(20) : collect([]);
        } else {
            $folds = collect([]);
        }
        
        return view('folds.index', compact('folds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $cells = Cell::orderBy('name')->get();
        } elseif ($user->isCellLeader()) {
            $cell = $user->getLedCell();
            $cells = $cell ? collect([$cell]) : collect([]);
        } else {
            $cells = collect([]);
        }
        
        return view('folds.create', compact('cells'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Validate request
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cell_id' => 'required|exists:cells,id',
            'is_active' => 'boolean',
        ]);
        
        // Check if user can create fold in this cell
        if ($user->isCellLeader()) {
            $cell = $user->getLedCell();
            if (!$cell || $cell->id != $data['cell_id']) {
                abort(403, 'You can only create folds in your own cell.');
            }
        }
        
        try {
            Fold::create($data);
            return redirect()->route('folds.index')->with('success', 'Fold created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating fold: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Fold $fold)
    {
        $user = auth()->user();
        
        // Check if user can view this fold
        if ($user->isCellLeader()) {
            $cell = $user->getLedCell();
            if (!$cell || $cell->id != $fold->cell_id) {
                abort(403, 'You can only view folds in your own cell.');
            }
        }
        
        return view('folds.show', compact('fold'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fold $fold)
    {
        $user = auth()->user();
        
        // Check if user can edit this fold
        if ($user->isCellLeader()) {
            $cell = $user->getLedCell();
            if (!$cell || $cell->id != $fold->cell_id) {
                abort(403, 'You can only edit folds in your own cell.');
            }
        }
        
        if ($user->isAdmin()) {
            $cells = Cell::orderBy('name')->get();
        } elseif ($user->isCellLeader()) {
            $cell = $user->getLedCell();
            $cells = $cell ? collect([$cell]) : collect([]);
        } else {
            $cells = collect([]);
        }
        
        return view('folds.edit', compact('fold', 'cells'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fold $fold)
    {
        $user = auth()->user();
        
        // Check if user can edit this fold
        if ($user->isCellLeader()) {
            $cell = $user->getLedCell();
            if (!$cell || $cell->id != $fold->cell_id) {
                abort(403, 'You can only edit folds in your own cell.');
            }
        }
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cell_id' => 'required|exists:cells,id',
            'is_active' => 'boolean',
        ]);
        
        // Check if user can move fold to this cell
        if ($user->isCellLeader()) {
            $cell = $user->getLedCell();
            if (!$cell || $cell->id != $data['cell_id']) {
                abort(403, 'You can only move folds within your own cell.');
            }
        }
        
        try {
            $fold->update($data);
            return redirect()->route('folds.index')->with('success', 'Fold updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating fold: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fold $fold)
    {
        $user = auth()->user();
        
        // Check if user can delete this fold
        if ($user->isCellLeader()) {
            $cell = $user->getLedCell();
            if (!$cell || $cell->id != $fold->cell_id) {
                abort(403, 'You can only delete folds in your own cell.');
            }
        }
        
        try {
            $fold->delete();
            return redirect()->route('folds.index')->with('success', 'Fold deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting fold: ' . $e->getMessage());
        }
    }
} 