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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no')->unique();
            $table->date('date');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            
            // GD Info
            $table->string('gd_no');
            $table->date('gd_date');
            
            // Shipment Info
            $table->string('location')->nullable(); // KICT, Port Qasim, etc.
            $table->integer('container_count')->default(1);
            
            // Financials
            $table->decimal('agency_commission', 15, 2)->default(0);
            $table->decimal('sales_tax_percentage', 5, 2)->default(15);
            $table->decimal('sales_tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0); // Final balance to be paid by client
            
            // Misc
            $table->text('marks_and_nos')->nullable();
            $table->text('remarks')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
