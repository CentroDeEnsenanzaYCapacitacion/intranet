<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentExamWindow;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'crew_id',
        'course_id',
        'genre',
        'name',
        'surnames',
        'email',
        'phone',
        'cel_phone',
        'address',
        'pc',
        'colony',
        'municipality',
        'birthdate',
        'birthplace',
        'curp',
        'rfc',
        'nss',
        'sex',
        'marital_status',
        'nationality',
        'grade',
        'schedule_id',
        'payment_periodicity_id',
        'modality_id',
        'sabbatine',
        'profile_pic',
        'first_time',
    ];

    protected $guarded = [
        'tuition',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function setSabbatineAttribute($value)
    {
        $this->attributes['sabbatine'] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

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

    public function eubExamWindow()
    {
        return $this->hasOne(StudentExamWindow::class)->where('exam_key', 'eub');
    }

    public function documents()
    {
        return $this->belongsToMany(StudentDocument::class, 'student_document_statuses')
                    ->withPivot('uploaded');
    }
}
