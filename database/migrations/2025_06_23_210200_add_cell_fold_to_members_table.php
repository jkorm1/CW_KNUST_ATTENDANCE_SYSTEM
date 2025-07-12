<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (!Schema::hasColumn('members', 'cell_id')) {
                $table->unsignedBigInteger('cell_id')->nullable()->after('status');
            }
            if (!Schema::hasColumn('members', 'fold_id')) {
                $table->unsignedBigInteger('fold_id')->nullable()->after('cell_id');
            }
        });

        // Add foreign key constraints if they don't exist
        if (Schema::hasTable('members') && Schema::hasTable('cells')) {
            Schema::table('members', function (Blueprint $table) {
                if (!Schema::hasColumn('members', 'cell_id')) {
                    $table->foreign('cell_id')->references('id')->on('cells')->onDelete('set null');
                }
            });
        }

        if (Schema::hasTable('members') && Schema::hasTable('folds')) {
            Schema::table('members', function (Blueprint $table) {
                if (!Schema::hasColumn('members', 'fold_id')) {
                    $table->foreign('fold_id')->references('id')->on('folds')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'fold_id')) {
                $table->dropForeign(['fold_id']);
                $table->dropColumn('fold_id');
            }
            if (Schema::hasColumn('members', 'cell_id')) {
                $table->dropForeign(['cell_id']);
                $table->dropColumn('cell_id');
            }
        });
    }
}; 