<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;

class SampleMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = [
            [
                'name' => 'John Doe',
                'phone' => '1234567890',
                'status' => 'active',
            ],
            [
                'name' => 'Jane Smith',
                'phone' => '0987654321',
                'status' => 'active',
            ],
            [
                'name' => 'Mike Johnson',
                'phone' => '5555555555',
                'status' => 'active',
            ],
            [
                'name' => 'Sarah Wilson',
                'phone' => '1112223333',
                'status' => 'active',
            ],
            [
                'name' => 'David Brown',
                'phone' => '4445556666',
                'status' => 'inactive',
            ],
        ];

        foreach ($members as $memberData) {
            Member::firstOrCreate(
                ['phone' => $memberData['phone']],
                $memberData
            );
        }
    }
} 