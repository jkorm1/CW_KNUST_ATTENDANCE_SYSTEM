<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Models\Cell;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestLeadershipAssignment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:leadership-assignment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test leadership assignment by creating a member and assigning them as a cell leader';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing leadership assignment...');

        // Create a test member
        $member = Member::create([
            'name' => 'Test Cell Leader Member',
            'gender' => 'male',
            'phone' => '1234567890',
            'status' => 'member',
            'notes' => 'Test member for leadership assignment'
        ]);

        $this->info("Created member: {$member->name}");

        // Get the first cell
        $cell = Cell::first();
        if (!$cell) {
            $this->error('No cells found. Please run the cells seeder first.');
            return;
        }

        // Assign member as cell leader
        $cell->update(['cell_leader_id' => $member->id]);
        $this->info("Assigned {$member->name} as leader of {$cell->name}");

        // Create user account for the leader
        $email = $member->name . '@church.com';
        $password = 'password123';
        
        $user = User::create([
            'name' => $member->name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole('usher');
        
        $this->info("Created user account for {$member->name}:");
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");
        $this->info("Role: usher");

        $this->info('Leadership assignment test completed successfully!');
        $this->info('You can now login with the created credentials to test the system.');
    }
} 