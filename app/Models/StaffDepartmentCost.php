<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffDepartmentCost extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id', 'department_id', 'cost', 'is_roster'];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
