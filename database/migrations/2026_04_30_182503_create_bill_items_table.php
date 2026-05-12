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
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->constrained()->onDelete('cascade');
            $table->string('particular_name'); // Custom Duty, Wharfage, etc.
            $table->string('receipt_type')->nullable(); // P.D, P.Order, Cash
            $table->date('receipt_date')->nullable();
            
            $table->decimal('actual_amount', 15, 2)->default(0);
            $table->decimal('pay_order_amount', 15, 2)->default(0); // Amount covered by client
            
            $table->boolean('is_paid_by_agent')->default(false); // If true, full amount is billable
            $table->decimal('short_payment', 15, 2)->default(0); // actual - pay_order (if not paid by agent)
            $table->decimal('billable_amount', 15, 2)->default(0); // The amount actually added to the bill balance
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_items');
    }
};
