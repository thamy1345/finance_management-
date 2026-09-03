<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('receipt_number')->unique();
            $table->string('academic_year');
            $table->string('term');
            $table->decimal('amount_paid', 10, 2);
            $table->enum('payment_method', ['cash', 'mpesa', 'bank_transfer', 'cheque'])->default('cash');
            $table->string('mpesa_code')->nullable();
            $table->string('bank_reference')->nullable();
            $table->date('payment_date');
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};