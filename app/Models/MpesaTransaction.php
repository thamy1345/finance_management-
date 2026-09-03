<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MpesaTransaction extends Model
{
    protected $fillable = [
        'mpesa_code',
        'student_id',
        'admission_number',
        'phone',
        'payer_name',
        'amount',
        'trans_time',
        'raw_payload',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}