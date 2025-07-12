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
        Schema::table('first_timers', function (Blueprint $table) {
            // Drop foreign key if exists
            try {
                $table->dropForeign(['service_id']);
            } catch (\Exception $e) {}
            // Change column to string
            $table->string('service_id', 64)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('first_timers', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->nullable()->change();
            // Optionally, add back the foreign key if needed
            // $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
        });
    }
};
