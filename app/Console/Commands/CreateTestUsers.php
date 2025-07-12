<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTestUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test users with different roles for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = [
            [
                'name' => 'Test Admin',
                'email' => 'admin@test.com',
                'password' => 'password',
                'role' => 'admin'
            ],
            [
                'name' => 'Test Usher',
                'email' => 'usher@test.com',
                'password' => 'password',
                'role' => 'usher'
            ],
            [
                'name' => 'Test Cell Leader',
                'email' => 'cellleader@test.com',
                'password' => 'password',
                'role' => 'usher'
            ],
            [
                'name' => 'Test Fold Leader',
                'email' => 'foldleader@test.com',
                'password' => 'password',
                'role' => 'usher'
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
            ]);

            $user->assignRole($userData['role']);

            $this->info("Created user: {$userData['name']} ({$userData['email']}) with role: {$userData['role']}");
        }

        $this->info('Test users created successfully!');
        $this->info('You can now login with any of these accounts using password: "password"');
    }
} 