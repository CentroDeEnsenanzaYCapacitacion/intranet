<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function requestType() {
        return $this->belongsTo(RequestType::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function report() {
        return $this->belongsTo(Report::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}
