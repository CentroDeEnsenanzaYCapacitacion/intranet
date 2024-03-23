<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'sabbatine' => 'boolean'
    ];

    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function observations()
    {
        return $this->hasMany(Observation::class)->orderBy('created_at', 'desc');
    }

    public function tutor()
    {
        return $this->hasOne(Tutor::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
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

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function documents()
    {
        return $this->belongsToMany(StudentDocument::class, 'student_document_statuses')
                    ->withPivot('uploaded');
    }
}
