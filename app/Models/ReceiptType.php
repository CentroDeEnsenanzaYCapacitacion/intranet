<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function recipes()
    {
        return $this->hasMany(Receipt::class);
    }

    public function amounts()
    {
        return $this->hasMany(Amount::class);
    }
}
