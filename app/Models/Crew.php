<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crew extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function courses() {
        return $this->hasMany(Course::class);
    }

    public function reports() {
        return $this->hasMany(Report::class);
    }

    public function receipts() {
        return $this->hasMany(Receipt::class);
    }

    public function amounts() {
        return $this->hasMany(Amount::class);
    }

    public function students() {
        return $this->hasMany(Student::class);
    }
}
