<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function documentation()
    {
        return $this->hasOne(Documentation::class);
    }

    public function tutor()
    {
        return $this->hasOne(Tutor::class);
    }

    public function generation()
    {
        return $this->belongsTo(Generation::class);
    }

    public function modality()
    {
        return $this->belongsTo(Modality::class);
    }

    public function paymentPeriodicity()
    {
        return $this->belongsTo(PaymentPeriodicity::class);
    }


}
