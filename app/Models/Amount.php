<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amount extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function crew() {
        return $this->belongsTo(Crew::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function receiptType() {
        return $this->belongsTo(ReceiptType::class);
    }
}
