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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendeer_id')->constrained('users')->cascadeOnDelete(); // attendee
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_amount', 10, 2);
            $table->integer('qty')->nullable();
            $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending');
            $table->enum('payment_method',['1', '2'])->comment('1=stripe, 2=paypal')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['attendeer_id']);  // Drop the foreign key constraint on the 'organizer_id' column
            $table->dropForeign(['ticket_id']);
        });
        Schema::dropIfExists('orders');
    }
};
