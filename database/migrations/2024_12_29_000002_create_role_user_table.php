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
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete(); // Role ID
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // User ID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropForeign(['role_id']);  // Drop the foreign key constraint on the 'organizer_id' column
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('role_user');
    }
};
