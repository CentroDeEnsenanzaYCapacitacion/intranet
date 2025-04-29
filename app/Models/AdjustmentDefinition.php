<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdjustmentDefinition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
    ];

    public function staffAdjustments()
    {
        return $this->hasMany(StaffAdjustment::class);
    }
}
