<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipeType()
    {
        return $this->belongsTo(ReceiptType::class);
    }

    public function payment()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
