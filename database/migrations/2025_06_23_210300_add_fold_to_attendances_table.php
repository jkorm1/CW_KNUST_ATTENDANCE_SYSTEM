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
        Schema::table('attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('attendances', 'fold_id')) {
                $table->unsignedBigInteger('fold_id')->nullable()->after('service_id');
            }
            if (!Schema::hasColumn('attendances', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('present');
            }
            if (!Schema::hasColumn('attendances', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
            if (!Schema::hasColumn('attendances', 'submitted_by')) {
                $table->unsignedBigInteger('submitted_by')->nullable()->after('notes'); // Fold leader who submitted
            }
            if (!Schema::hasColumn('attendances', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('submitted_by'); // Cell leader who approved
            }
            if (!Schema::hasColumn('attendances', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
        });

        // Add foreign key constraints if they don't exist
        if (Schema::hasTable('attendances') && Schema::hasTable('folds')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (!Schema::hasColumn('attendances', 'fold_id')) {
                    $table->foreign('fold_id')->references('id')->on('folds')->onDelete('cascade');
                }
            });
        }

        if (Schema::hasTable('attendances') && Schema::hasTable('users')) {
            Schema::table('attendances', function (Blueprint $table) {
                if (!Schema::hasColumn('attendances', 'submitted_by')) {
                    $table->foreign('submitted_by')->references('id')->on('users')->onDelete('set null');
                }
                if (!Schema::hasColumn('attendances', 'approved_by')) {
                    $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('attendances', 'approved_by')) {
                $table->dropForeign(['approved_by']);
                $table->dropColumn('approved_by');
            }
            if (Schema::hasColumn('attendances', 'submitted_by')) {
                $table->dropForeign(['submitted_by']);
                $table->dropColumn('submitted_by');
            }
            if (Schema::hasColumn('attendances', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('attendances', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('attendances', 'fold_id')) {
                $table->dropForeign(['fold_id']);
                $table->dropColumn('fold_id');
            }
        });
    }
}; 