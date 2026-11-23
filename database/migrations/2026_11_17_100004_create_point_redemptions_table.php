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
        Schema::create('point_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('points_spent');
            $table->decimal('rupiah_amount', 12, 2);
            $table->enum('status', ['processing', 'completed', 'cancelled'])->default('processing');
            $table->string('payment_method')->nullable(); // misal: Transfer Bank BCA, GoPay, OVO, Cash
            $table->string('account_details')->nullable(); // Nomor rekening/e-wallet & nama pemilik
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_redemptions');
    }
};
