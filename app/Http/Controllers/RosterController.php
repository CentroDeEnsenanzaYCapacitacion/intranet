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

    $year = $request->input('year');
    $month = $request->input('month');
    $period = $request->input('period');

    $startDate = null;
    $endDate = null;

    if ($year && $month && $period) {
        [$startDay, $endDay] = explode('-', $period);

        $startDate = Carbon::create($year, $month, $startDay)->startOfDay();
        $endDate = ((int) $endDay < (int) $startDay)
            ? Carbon::create($year, $month, $startDay)->addMonth()->day((int) $endDay)->endOfDay()
            : Carbon::create($year, $month, $endDay)->endOfDay();
    }

    // Obtener asignaciones de horas dependiendo del usuario y selección
    $query = HourAssignment::with('staff')
        ->whereBetween('date', [$startDate, $endDate]);

    if ($userCrewId != 1) {
        $query->where('crew_id', $userCrewId);
    } elseif ($selectedCrew && $selectedCrew != 1) {
        $query->where('crew_id', $selectedCrew);
    }

    $assignments = $query->get();

    // Agrupar por plantel donde se impartieron las clases
    $assignmentsGroupedByCrew = $assignments->groupBy('crew_id');

    $staffGrouped = [];
    $totalHoursByCrew = [];
    $totalHoursByStaff = [];
    $totalHours = $assignments->sum('hours');

    foreach ($assignmentsGroupedByCrew as $crewId => $groupAssignments) {
        $staffs = $groupAssignments->groupBy('staff_id');

        $staffGrouped[$crewId] = collect();

        foreach ($staffs as $staffId => $staffAssignments) {
            $staffInfo = $staffAssignments->first()->staff;
            if ($staffInfo) {
                $staffGrouped[$crewId]->push($staffInfo);
                $totalHoursByStaff[$staffId] = $staffAssignments->sum('hours');
            }
        }

        $totalHoursByCrew[$crewId] = $groupAssignments->sum('hours');
    }

    $assignments = HourAssignment::whereBetween('date', [$startDate, $endDate])->get();


    return view('admin.rosters.rosters.panel', compact(
        'crews',
        'staffGrouped',
        'totalHours',
        'totalHoursByCrew',
        'totalHoursByStaff',
        'assignments'
    ));

}


}
