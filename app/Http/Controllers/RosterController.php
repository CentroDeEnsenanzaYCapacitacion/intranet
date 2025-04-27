<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Crew;
use App\Models\HourAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\StaffRequest;

class RosterController extends Controller
{
    public function index()
    {
        $staff = Staff::where('isActive', true)->get();
        return view('admin.rosters.staff.show', compact('staff'));
    }

    public function create()
    {
        $crews = [];
        if (auth()->user()->crew_id == 1) {
            $crews = Crew::where('id', '!=', 1)->get();
        }
        return view('admin.rosters.staff.new', compact('crews'));
    }

    public function store(StaffRequest $request)
    {
        $data = $request->validated();

        $staff = new Staff();
        $staff->name = $data['name'];
        $staff->surnames = $data['surnames'] ?? null;
        $staff->Address = $data['Address'] ?? null;
        $staff->colony = $data['colony'] ?? null;
        $staff->municipalty = $data['municipalty'] ?? null;
        $staff->phone = $data['phone'] ?? null;
        $staff->cel = $data['cel'] ?? null;
        $staff->rfc = $data['rfc'] ?? null;
        $staff->department = $data['department'] ?? null;
        $staff->position = $data['position'] ?? null;
        $staff->personal_mail = $data['personal_mail'] ?? null;
        $staff->requiresMail = $request->has('requiresMail');
        $staff->isRoster = $request->has('isRoster');
        $staff->isActive = true;

        $staff->crew_id = auth()->user()->crew_id == 1
            ? $request->input('crew_id')
            : auth()->user()->crew_id;

        $staff->save();

        return redirect()->route('admin.staff.show')->with('success', 'Empleado creado exitosamente.');
    }

    public function deactivate($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->isActive = false;
        $staff->save();

        return redirect()->route('admin.staff.show')->with('success', 'Empleado eliminado correctamente.');
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        return view('admin.rosters.staff.edit', compact('staff'));
    }

    public function editSchedule()
    {
        return view('admin.rosters.schedule.edit');
    }

    public function update(StaffRequest $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $data = $request->validated();

        $staff->update([
            ...collect($data)->except(['name', 'surnames', 'cec_mail', 'requiresMail'])->toArray(),
            'isRoster' => $request->has('isRoster'),
        ]);

        return redirect()->route('admin.staff.show')->with('success', 'Empleado actualizado correctamente.');
    }

    public function rosters(Request $request)
    {
        $crews = Crew::all();
        $userCrewId = auth()->user()->crew_id;
        $selectedCrew = $request->get('crew');

        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $period = $request->input('period', '8-22');

        $startDate = null;
        $endDate = null;
        $periodDays = null;

        if ($year && $month && $period) {
            [$startDay, $endDay] = explode('-', $period);

            $startDate = Carbon::create($year, $month, $startDay)->startOfDay();
            $endDate = ((int) $endDay < (int) $startDay)
                ? Carbon::create($year, $month, $startDay)->addMonth()->day((int) $endDay)->endOfDay()
                : Carbon::create($year, $month, $endDay)->endOfDay();

            $periodDays = $startDate->diffInDays($endDate) + 1;
        }


        $staffQuery = Staff::where('isActive', true)->where('crew_id', '!=', 1);

        if ($userCrewId != 1) {
            $staffQuery->where('crew_id', $userCrewId);
        } elseif ($selectedCrew && $selectedCrew != 1) {
            $staffQuery->where('crew_id', $selectedCrew);
        }

        $staffAll = $staffQuery->get();

        $assignments = HourAssignment::whereBetween('date', [$startDate, $endDate])->get();

        $staffGrouped = [];

        if ($selectedCrew && $selectedCrew != 1) {
            if (!isset($staffGrouped[$selectedCrew])) {
                $staffGrouped[$selectedCrew] = collect();
            }
        }

        $totalHoursByCrew = [];
        $totalHoursByStaff = [];
        $totalHours = 0;

        foreach ($staffAll as $staff) {
            $hours = $assignments->where('staff_id', $staff->id)->where('crew_id', $staff->crew_id);
            $hoursSum = (float) number_format($hours->sum('hours'), 1, '.', '');

            $totalHoursByStaff[$staff->id] = $hoursSum;

            if (!isset($staffGrouped[$staff->crew_id])) {
                $staffGrouped[$staff->crew_id] = collect();
            }
            $staffGrouped[$staff->crew_id]->push($staff);

            if (!isset($totalHoursByCrew[$staff->crew_id])) {
                $totalHoursByCrew[$staff->crew_id] = 0;
            }
            $totalHoursByCrew[$staff->crew_id] += $hoursSum;

            $totalHours += $hoursSum;
        }

        if ($userCrewId == 1 && (!$selectedCrew || $selectedCrew == 1)) {
            foreach ($crews as $crew) {
                if (!isset($staffGrouped[$crew->id]) && $crew->id != 1) {
                    $staffGrouped[$crew->id] = collect();
                    $totalHoursByCrew[$crew->id] = 0;
                }
            }
        }

        return view('admin.rosters.rosters.panel', compact(
            'crews',
            'staffGrouped',
            'totalHours',
            'totalHoursByCrew',
            'totalHoursByStaff',
            'assignments',
            'periodDays'
        ));

    }


}
