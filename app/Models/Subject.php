<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active', 'course_id'];

    public function hourAssignments()
    {
        return $this->hasMany(HourAssignment::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
