<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function mailRequest()
    {
        return $this->hasOne(MailRequest::class);
    }

    public function hourAssignments()
    {
        return $this->hasMany(HourAssignment::class);
    }

    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

    public function adjustments()
    {
        return $this->hasMany(StaffAdjustment::class);
    }

    public function departmentCosts()
    {
        return $this->hasMany(StaffDepartmentCost::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'staff_department_costs')
            ->withPivot('cost', 'is_roster')
            ->withTimestamps();
    }
}
