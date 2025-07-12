<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cell;
use App\Models\Fold;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestLeadershipSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:leadership-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test data for the leadership system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating test leadership system...');

        // Create test cells
        $cell1 = Cell::create([
            'name' => 'Cell A',
            'description' => 'Test Cell A',
            'location' => 'Location A',
            'is_active' => true,
        ]);

        $cell2 = Cell::create([
            'name' => 'Cell B',
            'description' => 'Test Cell B',
            'location' => 'Location B',
            'is_active' => true,
        ]);

        // Create test folds
        $fold1 = Fold::create([
            'name' => 'Fold 1',
            'description' => 'Test Fold 1',
            'cell_id' => $cell1->id,
            'is_active' => true,
        ]);

        $fold2 = Fold::create([
            'name' => 'Fold 2',
            'description' => 'Test Fold 2',
            'cell_id' => $cell1->id,
            'is_active' => true,
        ]);

        // Create test members
        $members = [
            [
                'name' => 'John Cell Leader',
                'gender' => 'male',
                'phone' => '1234567890',
                'status' => 'member',
                'notes' => 'Cell Leader for Cell A'
            ],
            [
                'name' => 'Jane Fold Leader',
                'gender' => 'female',
                'phone' => '0987654321',
                'status' => 'member',
                'notes' => 'Fold Leader for Fold 1'
            ],
            [
                'name' => 'Bob Member',
                'gender' => 'male',
                'phone' => '5555555555',
                'status' => 'member',
                'notes' => 'Regular member in Cell A'
            ],
            [
                'name' => 'Alice Member',
                'gender' => 'female',
                'phone' => '4444444444',
                'status' => 'member',
                'notes' => 'Regular member in Fold 1'
            ]
        ];

        foreach ($members as $memberData) {
            $member = Member::create($memberData);
            $this->info("Created member: {$member->name}");
        }

        // Assign leaders
        $cellLeader = Member::where('name', 'John Cell Leader')->first();
        $foldLeader = Member::where('name', 'Jane Fold Leader')->first();

        if ($cellLeader) {
            $cell1->update(['cell_leader_id' => $cellLeader->id]);
            $this->createUserForLeader($cellLeader, 'usher');
            $this->info("Assigned {$cellLeader->name} as Cell Leader for {$cell1->name}");
        }

        if ($foldLeader) {
            $fold1->update(['fold_leader_id' => $foldLeader->id]);
            $this->createUserForLeader($foldLeader, 'usher');
            $this->info("Assigned {$foldLeader->name} as Fold Leader for {$fold1->name}");
        }

        // Add members to cells/folds
        $bob = Member::where('name', 'Bob Member')->first();
        $alice = Member::where('name', 'Alice Member')->first();

        if ($bob) {
            $bob->update(['cell_id' => $cell1->id]);
            $this->info("Added {$bob->name} to {$cell1->name}");
        }

        if ($alice) {
            $alice->update(['fold_id' => $fold1->id]);
            $this->info("Added {$alice->name} to {$fold1->name}");
        }

        $this->info('Test leadership system created successfully!');
        $this->info('You can now test the system with the created leaders.');
    }

    private function createUserForLeader(Member $member, $role = 'usher')
    {
        $email = $member->email ?? $member->name . '@church.com';
        $password = 'password123';
        
        $user = User::create([
            'name' => $member->name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole($role);
        
        $this->info("Created user account for {$member->name}: Email: {$email}, Password: {$password}");
    }
} 