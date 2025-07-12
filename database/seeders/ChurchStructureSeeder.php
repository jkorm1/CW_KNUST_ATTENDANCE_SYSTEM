<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cell;
use App\Models\Fold;
use App\Models\Member;
use App\Models\Service;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ChurchStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Cells (using actual schema)
        $cells = [
            [
                'name' => 'Zoe Cell',
                'description' => 'The God Life',
                'location' => 'Main Hall',
                'is_active' => true,
            ],
            [
                'name' => 'Dunamis Cell',
                'description' => 'Power at Work',
                'location' => 'Side Room A',
                'is_active' => true,
            ],
            [
                'name' => 'Makarious Cell',
                'description' => 'Love',
                'location' => 'Side Room B',
                'is_active' => true,
            ],
        ];

        foreach ($cells as $cellData) {
            Cell::create($cellData);
        }

        // Create Folds for each Cell
        $folds = [
            // Zoe Cell Folds
            [
                'name' => 'Zoe Fold 1',
                'description' => 'First fold of Zoe Cell',
                'cell_id' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Zoe Fold 2',
                'description' => 'Second fold of Zoe Cell',
                'cell_id' => 1,
                'is_active' => true,
            ],
            
            // Dunamis Cell Folds
            [
                'name' => 'Dunamis Fold 1',
                'description' => 'First fold of Dunamis Cell',
                'cell_id' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Dunamis Fold 2',
                'description' => 'Second fold of Dunamis Cell',
                'cell_id' => 2,
                'is_active' => true,
            ],
            
            // Makarious Cell Folds
            [
                'name' => 'Makarious Fold 1',
                'description' => 'First fold of Makarious Cell',
                'cell_id' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Makarious Fold 2',
                'description' => 'Second fold of Makarious Cell',
                'cell_id' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($folds as $foldData) {
            Fold::create($foldData);
        }

        // Create Members with Christian names
        $members = [
            // Zoe Cell Members
            [
                'name' => 'Grace Abena Osei',
                'phone' => '+233244123456',
                'gender' => 'Female',
                'status' => 'member',
                'location' => 'KNUST Campus, Kumasi',
                'notes' => 'Cell Leader - Zoe Cell',
            ],
            [
                'name' => 'Emmanuel Kwame Addo',
                'phone' => '+233244123458',
                'gender' => 'Male',
                'status' => 'member',
                'location' => 'KNUST Campus, Kumasi',
                'notes' => 'Member - Zoe Fold 1',
            ],
            [
                'name' => 'Faith Ama Kufuor',
                'phone' => '+233244123460',
                'gender' => 'Female',
                'status' => 'member',
                'location' => 'KNUST Campus, Kumasi',
                'notes' => 'Fold Leader - Zoe Fold 2',
            ],
            [
                'name' => 'Daniel Kwesi Mensah',
                'phone' => '+233244123462',
                'gender' => 'Male',
                'status' => 'member',
                'location' => 'KNUST Campus, Kumasi',
                'notes' => 'Member - Zoe Fold 2',
            ],

            // Dunamis Cell Members
            [
                'name' => 'David Kwame Asante',
                'phone' => '+233244123464',
                'gender' => 'Male',
                'status' => 'member',
                'location' => 'KNUST Campus, Kumasi',
                'notes' => 'Cell Leader - Dunamis Cell',
            ],
            [
                'name' => 'Ruth Abena Owusu',
                'phone' => '+233244123466',
                'gender' => 'Female',
                'status' => 'member',
                'location' => 'KNUST Campus, Kumasi',
                'notes' => 'Member - Dunamis Fold 1',
            ],
            [
                'name' => 'Samuel Kwesi Boateng',
                'phone' => '+233244123468',
                'gender' => 'Male',
                'status' => 'member',
                'location' => 'KNUST Campus, Kumasi',
                'notes' => 'Fold Leader - Dunamis Fold 2',
            ],
            [
                'name' => 'Hannah Ama Sarpong',
                'phone' => '+233244123470',
                'gender' => 'Female',
                'status' => 'member',
                'location' => 'KNUST Campus, Kumasi',
                'notes' => 'Member - Dunamis Fold 2',
            ],

            // Makarious Cell Members
            [
                'name' => 'Sarah Abena Darko',
                'phone' => '+233244123472',
                'gender' => 'Female',
                'status' => 'member',
                'location' => 'KNUST Campus, Kumasi',
                'notes' => 'Cell Leader - Makarious Cell',
            ],
            [
                'name' => 'Joseph Kwame Ampah',
                'phone' => '+233244123474',
                'gender' => 'Male',
                'status' => 'member',
                'location' => 'KNUST Campus, Kumasi',
                'notes' => 'Member - Makarious Fold 1',
            ],
            [
                'name' => 'Esther Ama Tetteh',
                'phone' => '+233244123476',
                'gender' => 'Female',
                'status' => 'member',
                'location' => 'KNUST Campus, Kumasi',
                'notes' => 'Fold Leader - Makarious Fold 2',
            ],
            [
                'name' => 'Michael Kwesi Ankrah',
                'phone' => '+233244123478',
                'gender' => 'Male',
                'status' => 'member',
                'location' => 'KNUST Campus, Kumasi',
                'notes' => 'Member - Makarious Fold 2',
            ],
        ];

        foreach ($members as $memberData) {
            Member::create($memberData);
        }

        $this->command->info('Church structure created successfully!');
    }
}