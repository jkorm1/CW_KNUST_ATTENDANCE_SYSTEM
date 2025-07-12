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
        if (!Schema::hasTable('first_timers')) {
            Schema::create('first_timers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone')->nullable();
                $table->string('gender')->nullable();
                $table->string('location')->nullable();
                $table->unsignedBigInteger('invited_by')->nullable();
                $table->unsignedBigInteger('cell_id')->nullable();
                $table->unsignedBigInteger('fold_id')->nullable();
                $table->date('first_visit_date')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('invited_by')->references('id')->on('members')->nullOnDelete();
                $table->foreign('cell_id')->references('id')->on('cells')->nullOnDelete();
                $table->foreign('fold_id')->references('id')->on('folds')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('first_timers');
    }
}; 