<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function students() {
        return $this->belongsToMany(Student::class,'student_document_statuses')
                    ->withPivot('uploaded');
    }
}
