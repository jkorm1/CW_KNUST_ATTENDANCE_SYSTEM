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
        Schema::create('folds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('cell_id');
            $table->unsignedBigInteger('fold_leader_id')->nullable();
            $table->unsignedBigInteger('assistant_leader_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('cell_id')->references('id')->on('cells')->onDelete('cascade');
            $table->foreign('fold_leader_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assistant_leader_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folds');
    }
};
