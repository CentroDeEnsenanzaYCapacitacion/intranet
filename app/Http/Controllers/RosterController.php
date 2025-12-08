<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Crew;
use App\Models\HourAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\StaffRequest;
use App\Models\StaffAdjustment;

class RosterController extends Controller
{
    public function __construct()
    {
        // Proteger gestión de nómina
        $this->middleware('role:1,2,4');
    }

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

    public function storeAdjustment(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'definition_id' => 'required|exists:adjustment_definitions,id',
            'amount' => 'required|numeric|min:0',
            'year' => 'required|integer',
            'month' => 'required|integer',
            'period' => 'required|string',
            'crew_id' => 'required|exists:crews,id',
        ]);

        StaffAdjustment::create([
            'staff_id' => $validated['staff_id'],
            'adjustment_definition_id' => $validated['definition_id'],
            'amount' => $validated['amount'],
            'year' => $validated['year'],
            'month' => $validated['month'],
            'period' => $validated['period'],
            'crew_id' => $validated['crew_id'],
        ]);

        return redirect()->back()->with('success', 'Ajuste agregado correctamente.');
    }


    public function destroyAdjustment($id)
    {
        $adjustment = StaffAdjustment::findOrFail($id);
        $adjustment->delete();

        return redirect()->back()->with('success', 'Ajuste eliminado correctamente.');
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
        $staff->cost = $data['cost'];
        $staff->isRoster = $data['cost_type'] === 'day';
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
            ...collect($data)->except(['name', 'surnames', 'cost_type'])->toArray(),
            'cost' => $data['cost'],
            'isRoster' => ($data['cost_type'] ?? 'day') === 'day',
        ]);

        return redirect()->route('admin.staff.show')->with('success', 'Empleado actualizado correctamente.');
    }

    public function rosters(Request $request)
    {
        $userCrewId = auth()->user()->crew_id;
        $selectedCrew = $request->get('crew');

        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $period = $request->input('period', '8-22');

        [$startDay, $endDay] = explode('-', $period);

        $startDate = Carbon::create($year, $month, $startDay)->startOfDay();
        $endDate = ((int) $endDay < (int) $startDay)
            ? Carbon::create($year, $month, $startDay)->addMonth()->day((int) $endDay)->endOfDay()
            : Carbon::create($year, $month, $endDay)->endOfDay();

        $allCrews = Crew::where('id', '!=', 1)->get();

        if ($userCrewId == 1) {
            if ($selectedCrew && $selectedCrew != 'all') {
                $crewsToShow = Crew::where('id', $selectedCrew)->get();
            } else {
                $crewsToShow = $allCrews;
            }
        } else {
            $crewsToShow = Crew::where('id', $userCrewId)->get();
        }

        $hourAssignments = HourAssignment::with('staff')
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $assignments = $hourAssignments;

        $staffGrouped = [];
        $totalHoursByCrew = [];
        $totalHoursByStaff = [];
        $totalHours = 0;
        $periodDays = (int) Carbon::parse($startDate)->diffInDays($endDate) + 1;

        $totalCostByCrew = [];
        $totalCostByStaff = [];
        $totalCost = 0.0;

        foreach ($crewsToShow as $crew) {

            $staffWithHours = $hourAssignments
                ->where('crew_id', $crew->id)
                ->pluck('staff')
                ->unique('id')
                ->map(function ($staff) use ($year, $month, $period, $crew) {
                    $staff->filtered_adjustments = $staff->adjustments()
                        ->where('year', $year)
                        ->where('month', $month)
                        ->where('period', $period)
                        ->where('crew_id', $crew->id)
                        ->with('adjustmentDefinition')
                        ->get();
                    return $staff;
                });

            $rosterStaff = Staff::where('crew_id', $crew->id)
                ->where('isActive', true)
                ->where('isRoster', true)
                ->get()
                ->map(function ($staff) use ($year, $month, $period, $crew) {
                    $staff->filtered_adjustments = $staff->adjustments()
                        ->where('year', $year)
                        ->where('month', $month)
                        ->where('period', $period)
                        ->where('crew_id', $crew->id)
                        ->with('adjustmentDefinition')
                        ->get();
                    return $staff;
                });



            $mergedStaff = $staffWithHours->merge($rosterStaff)->unique('id')->map(function ($staff) use ($year, $month, $period, $crew) {
                if (!isset($staff->filtered_adjustments)) {
                    $staff->filtered_adjustments = $staff->adjustments()
                        ->where('year', $year)
                        ->where('month', $month)
                        ->where('period', $period)
                        ->where('crew_id', $crew->id)
                        ->with('adjustmentDefinition')
                        ->get();
                }
                return $staff;
            });


            $staffGrouped[$crew->id] = $mergedStaff;

            $crewHours = $hourAssignments
                ->where('crew_id', $crew->id)
                ->sum('hours');

            $totalHoursByCrew[$crew->id] = (float) number_format($crewHours, 1, '.', '');

            foreach ($mergedStaff as $staff) {
                $hours = $hourAssignments
                    ->where('crew_id', $crew->id)
                    ->where('staff_id', $staff->id)
                    ->sum('hours');

                $totalHoursByStaff[$staff->id] = (float) number_format($hours, 2, '.', '');

                $staffCost = 0.0;
                if ($staff->isRoster) {
                    $staffCost = $staff->cost * $periodDays;
                } else {
                    $staffCost = $staff->cost * $hours;
                }

                if (!isset($totalCostByCrew[$crew->id])) {
                    $totalCostByCrew[$crew->id] = 0;
                }

                $totalCostByCrew[$crew->id] += $staffCost;
                $totalCostByStaff[$staff->id] = $staffCost;
                $totalCost += $staffCost;
            }
        }

        $totalHours = (float) number_format($hourAssignments->sum('hours'), 1, '.', '');

        $adjustedTotalCost = 0;

        foreach ($staffGrouped as $crewId => $staffList) {
            foreach ($staffList as $staff) {
                $baseCost = $staff->isRoster
                    ? $staff->cost * $periodDays
                    : $assignments->where('crew_id', $crewId)->where('staff_id', $staff->id)->sum('hours') * $staff->cost;

                $adjustments = $staff->filtered_adjustments ?? collect();

                $adjSum = 0;
                foreach ($adjustments as $adj) {
                    $adjSum += $adj->adjustmentDefinition->type === 'perception'
                        ? $adj->amount
                        : -$adj->amount;
                }

                $adjustedTotalCost += ($baseCost + $adjSum);
            }
        }

        $adjustmentDefinitions = \App\Models\AdjustmentDefinition::all();


        return view('admin.rosters.rosters.panel', compact(
            'allCrews',
            'crewsToShow',
            'staffGrouped',
            'totalHoursByCrew',
            'totalHoursByStaff',
            'assignments',
            'totalHours',
            'periodDays',
            'totalCostByCrew',
            'totalCostByStaff',
            'totalCost',
            'adjustmentDefinitions',
            'adjustedTotalCost'
        ));
    }
}
