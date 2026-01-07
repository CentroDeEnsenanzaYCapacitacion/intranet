<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExamWindow extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'exam_key',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
