<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mpesa_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('mpesa_code')->unique();         // M-Pesa transaction ID e.g. RGH7Y3XXXX
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->string('admission_number');             // raw BillRefNumber from payload
            $table->string('phone')->nullable();            // payer phone
            $table->string('payer_name')->nullable();       // payer name from Safaricom
            $table->decimal('amount', 12, 2);
            $table->string('trans_time');                   // raw YmdHis string
            $table->longText('raw_payload');                // full JSON for audit
            $table->timestamps();
        });

        // Add mpesa columns to fee_payments if not already present
        Schema::table('fee_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('fee_payments', 'mpesa_code')) {
                $table->string('mpesa_code')->nullable()->after('receipt_number');
            }
            if (!Schema::hasColumn('fee_payments', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('mpesa_code');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mpesa_transactions');

        Schema::table('fee_payments', function (Blueprint $table) {
            $table->dropColumnIfExists('mpesa_code');
            $table->dropColumnIfExists('phone_number');
        });
    }
};