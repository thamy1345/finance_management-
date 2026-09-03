<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->id();
            $table->enum('grade', ['1','2','3','4','5','6','7','8','9']);
            $table->string('academic_year'); // e.g. 2024
            $table->string('term'); // Term 1, Term 2, Term 3
            $table->decimal('tuition_fee', 10, 2)->default(0);
            $table->decimal('activity_fee', 10, 2)->default(0);
            $table->decimal('exam_fee', 10, 2)->default(0);
            $table->decimal('boarding_fee', 10, 2)->default(0);
            $table->decimal('transport_fee', 10, 2)->default(0);
            $table->decimal('other_fee', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['grade', 'academic_year', 'term']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};