<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name',
        'roll_number',
        'reason',
        'leave_type',
        'from_date',
        'to_date'
    ];
}
