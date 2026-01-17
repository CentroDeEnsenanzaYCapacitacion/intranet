<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function amounts()
    {
        return $this->hasMany(Amount::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
