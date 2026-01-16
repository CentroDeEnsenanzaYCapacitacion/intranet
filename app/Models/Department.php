<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function staffCosts()
    {
        return $this->hasMany(StaffDepartmentCost::class);
    }

    public function staff()
    {
        return $this->belongsToMany(Staff::class, 'staff_department_costs')
            ->withPivot('cost', 'is_roster')
            ->withTimestamps();
    }
}
