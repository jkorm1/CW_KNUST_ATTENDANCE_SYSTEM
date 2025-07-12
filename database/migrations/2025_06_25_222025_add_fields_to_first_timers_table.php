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
            $table->date('date_of_birth')->nullable()->after('name');
            $table->string('residence')->nullable()->after('phone');
            $table->enum('purpose', ['visit', 'stay'])->default('visit')->after('residence');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('first_timers', function (Blueprint $table) {
            $table->dropColumn(['date_of_birth', 'residence', 'purpose']);
        });
    }
};
