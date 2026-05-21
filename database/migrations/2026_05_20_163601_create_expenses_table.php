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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->decimal('bonus', 15, 2)->default(0)->nullable();
            $table->decimal('deduction', 15, 2)->default(0)->nullable();
            $table->date('salary_month')->nullable();
            $table->decimal('final_amount', 15, 2)->nullable();
            $table->string('payment_method'); // Cash, Bank Transfer, Cheque, Online Transfer
            $table->string('reference_no')->nullable();
            $table->text('notes')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('status')->default('paid'); // paid, pending
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete(); // Added By
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
