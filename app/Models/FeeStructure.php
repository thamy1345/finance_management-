<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'grade', 'academic_year', 'term',
        'tuition_fee', 'activity_fee', 'exam_fee',
        'boarding_fee', 'transport_fee', 'other_fee',
    ];

    protected $casts = [
        'tuition_fee'  => 'float',
        'activity_fee' => 'float',
        'exam_fee'     => 'float',
        'boarding_fee' => 'float',
        'transport_fee'=> 'float',
        'other_fee'    => 'float',
    ];

    public function getTotalFeeAttribute(): float
    {
        return $this->tuition_fee + $this->activity_fee + $this->exam_fee
             + $this->boarding_fee + $this->transport_fee + $this->other_fee;
    }

    public function getGradeLabelAttribute(): string
    {
        return "Grade {$this->grade}";
    }
}