<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Member management
            'view members',
            'create members',
            'edit members',
            'delete members',
            
            // Attendance management
            'view attendance',
            'take attendance',
            'approve attendance',
            'export attendance',
            
            // Cell management
            'view cells',
            'create cells',
            'edit cells',
            'delete cells',
            
            // Fold management
            'view folds',
            'create folds',
            'edit folds',
            'delete folds',
            
            // Service management
            'view services',
            'create services',
            'edit services',
            'delete services',
            
            // Reports and analytics
            'view reports',
            'view analytics',
            'export reports',
            
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $apostleRole = Role::firstOrCreate(['name' => 'apostle']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $cellLeaderRole = Role::firstOrCreate(['name' => 'cell_leader']);
        $foldLeaderRole = Role::firstOrCreate(['name' => 'fold_leader']);

        // Assign permissions to roles
        $apostleRole->givePermissionTo(Permission::all());
        
        $adminRole->givePermissionTo([
            'view members', 'create members', 'edit members', 'delete members',
            'view attendance', 'take attendance', 'approve attendance', 'export attendance',
            'view cells', 'create cells', 'edit cells', 'delete cells',
            'view folds', 'create folds', 'edit folds', 'delete folds',
            'view services', 'create services', 'edit services', 'delete services',
            'view reports', 'view analytics', 'export reports',
            'view users', 'create users', 'edit users', 'delete users', 'assign roles',
        ]);

        $cellLeaderRole->givePermissionTo([
            'view members', 'create members', 'edit members',
            'view attendance', 'take attendance', 'approve attendance',
            'view cells', 'edit cells',
            'view folds', 'create folds', 'edit folds',
            'view services',
            'view reports', 'view analytics',
        ]);

        $foldLeaderRole->givePermissionTo([
            'view members',
            'view attendance', 'take attendance',
            'view cells',
            'view folds',
            'view services',
        ]);

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'victus@cwknust.com'],
            [
                'name' => 'Apostle Stephen Phenuma',
                'password' => Hash::make('password'),
            ]
        );

        $admin->assignRole('apostle');

        // Create additional admin user
        $admin2 = User::firstOrCreate(
            ['email' => 'admin@church.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
            ]
        );

        $admin2->assignRole('admin');
    }
}
