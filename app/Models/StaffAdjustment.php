<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAdjustment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function adjustmentDefinition()
    {
        return $this->belongsTo(AdjustmentDefinition::class);
    }
}
