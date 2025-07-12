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
        if (!Schema::hasTable('members')) {
            Schema::create('members', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('gender')->nullable();
                $table->string('phone')->nullable();
                $table->enum('status', ['first_timer', 'associate', 'member'])->default('first_timer');
                $table->unsignedBigInteger('invited_by')->nullable();
                $table->date('first_visit_date')->nullable();
                $table->integer('times_attended')->default(0);
                $table->integer('invitees_count')->default(0); // Gathere
                $table->integer('planters_count')->default(0); // Planter
                $table->date('date_converted')->nullable();
                $table->date('last_attended')->nullable();
                $table->text('notes')->nullable();
                if (!Schema::hasColumn('members', 'location')) {
                    $table->string('location')->nullable();
                }
                $table->timestamps();

                $table->foreign('invited_by')->references('id')->on('members')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
