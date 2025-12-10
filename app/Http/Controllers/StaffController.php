<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function __construct()
    {

        $this->middleware('role:1,2,4');
    }

    public function getAllStaff()
    {
        $staff = Staff::all();

        return view('admin.admin.rosters.staff.show', compact('staff'));
    }
}
