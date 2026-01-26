<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HourAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'subject_id',
        'crew_id',
        'department_id',
        'date',
        'start_time',
        'end_time',
        'hours'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
