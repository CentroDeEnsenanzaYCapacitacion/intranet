<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function mailRequest()
    {
        return $this->hasOne(MailRequest::class);
    }

    public function hourAssignments()
    {
        return $this->hasMany(HourAssignment::class);
    }

    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

}
