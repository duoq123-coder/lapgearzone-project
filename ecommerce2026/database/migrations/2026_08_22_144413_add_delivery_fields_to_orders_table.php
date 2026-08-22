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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('delivery_staff_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('delivery_status')->default('pending'); // pending, assigned, completed
            $table->text('delivery_proof')->nullable(); // JSON or text to store image paths
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['delivery_staff_id']);
            $table->dropColumn(['delivery_staff_id', 'delivery_status', 'delivery_proof']);
        });
    }
};
