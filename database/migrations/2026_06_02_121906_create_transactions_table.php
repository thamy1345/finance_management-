<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['income', 'expense']);
            $table->string('category');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('transaction_date');
            $table->string('reference_number')->nullable();
            $table->enum('payment_method', ['cash', 'mpesa', 'bank_transfer', 'cheque'])->default('cash');
            $table->enum('status', ['completed', 'pending', 'cancelled'])->default('completed');
            $table->string('academic_year');
            $table->string('term')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};