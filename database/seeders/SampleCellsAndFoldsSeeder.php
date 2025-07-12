<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cell;
use App\Models\Fold;
use App\Models\Member;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SampleCellsAndFoldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample cells
        $cells = [
            [
                'name' => 'Cell Alpha',
                'description' => 'Main cell for young adults',
                'location' => 'Main Hall',
                'is_active' => true,
            ],
            [
                'name' => 'Cell Beta',
                'description' => 'Cell for families',
                'location' => 'Side Room A',
                'is_active' => true,
            ],
            [
                'name' => 'Cell Gamma',
                'description' => 'Cell for seniors',
                'location' => 'Side Room B',
                'is_active' => true,
            ],
        ];

        foreach ($cells as $cellData) {
            Cell::create($cellData);
        }

        // Create sample folds within cells
        $folds = [
            [
                'name' => 'Fold 1 - Young Men',
                'description' => 'Young men group in Cell Alpha',
                'cell_id' => 1, // Cell Alpha
                'is_active' => true,
            ],
            [
                'name' => 'Fold 2 - Young Women',
                'description' => 'Young women group in Cell Alpha',
                'cell_id' => 1, // Cell Alpha
                'is_active' => true,
            ],
            [
                'name' => 'Fold 1 - Married Couples',
                'description' => 'Married couples in Cell Beta',
                'cell_id' => 2, // Cell Beta
                'is_active' => true,
            ],
            [
                'name' => 'Fold 2 - Single Parents',
                'description' => 'Single parents in Cell Beta',
                'cell_id' => 2, // Cell Beta
                'is_active' => true,
            ],
            [
                'name' => 'Fold 1 - Senior Men',
                'description' => 'Senior men in Cell Gamma',
                'cell_id' => 3, // Cell Gamma
                'is_active' => true,
            ],
            [
                'name' => 'Fold 2 - Senior Women',
                'description' => 'Senior women in Cell Gamma',
                'cell_id' => 3, // Cell Gamma
                'is_active' => true,
            ],
        ];

        foreach ($folds as $foldData) {
            Fold::create($foldData);
        }

        $this->command->info('Sample cells and folds created successfully!');
    }
} 