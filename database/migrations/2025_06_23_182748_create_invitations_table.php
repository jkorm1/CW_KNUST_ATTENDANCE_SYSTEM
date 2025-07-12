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
        if (!Schema::hasTable('invitations')) {
            Schema::create('invitations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('inviter_id'); // member who invited
                $table->unsignedBigInteger('invitee_id'); // member who was invited
                $table->date('invited_on')->nullable();
                $table->timestamps();

                $table->foreign('inviter_id')->references('id')->on('members')->cascadeOnDelete();
                $table->foreign('invitee_id')->references('id')->on('members')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
