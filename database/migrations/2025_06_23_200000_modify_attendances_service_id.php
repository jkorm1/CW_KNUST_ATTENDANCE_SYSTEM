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
            // Drop the foreign key constraint first
            $table->dropForeign(['service_id']);
            
            // Change the column type from unsignedBigInteger to string
            $table->string('service_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Change back to unsignedBigInteger
            $table->unsignedBigInteger('service_id')->change();
            
            // Re-add the foreign key constraint
            $table->foreign('service_id')->references('id')->on('services')->cascadeOnDelete();
        });
    }
}; 