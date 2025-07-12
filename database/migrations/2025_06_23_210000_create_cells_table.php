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
        if (!Schema::hasTable('cells')) {
            Schema::create('cells', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('location')->nullable();
                $table->unsignedBigInteger('cell_leader_id')->nullable(); // Main cell leader
                $table->unsignedBigInteger('assistant_leader_id')->nullable(); // Assistant cell leader
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('cell_leader_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('assistant_leader_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cells');
    }
}; 