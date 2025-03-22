<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function reports($period)
    {
        return view('admin.stats.reports', compact('period'));
    }

    public function billing()
    {
        return view('admin.stats.billing');
    }
}
