<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surnames',
        'email',
        'marketing_id',
        'crew_id',
        'phone',
        'genre',
        'cel_phone',
        'course_id',
        'responsible_id',
        'signed'
    ];

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function crew() {
        return $this->belongsTo(Crew::class);
    }

    public function marketing() {
        return $this->belongsTo(Marketing::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function request() {
        return $this->hasMany(SysRequest::class);
    }

    public function receipts() {
        return $this->hasMany(Receipt::class);
    }
}
