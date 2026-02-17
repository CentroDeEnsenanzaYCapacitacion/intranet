<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Crew;
use App\Models\Department;
use App\Models\HourAssignment;
use App\Models\StaffDepartmentCost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\StaffRequest;
use App\Models\StaffAdjustment;

class RosterController extends Controller
{
    public function __construct()
    {

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
        $departments = Department::where('is_active', true)->get();
        return view('admin.rosters.staff.new', compact('crews', 'departments'));
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

        $userCrewId = auth()->user()->crew_id;
        if ($userCrewId != 1 && $adjustment->crew_id != $userCrewId) {
            abort(403);
        }

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
        $staff->isActive = true;

        $staff->crew_id = auth()->user()->crew_id == 1
            ? $request->input('crew_id')
            : auth()->user()->crew_id;

        $staff->save();

        if ($request->has('departments')) {
            foreach ($request->input('departments') as $deptData) {
                if (!empty($deptData['department_id']) && isset($deptData['cost'])) {
                    StaffDepartmentCost::create([
                        'staff_id' => $staff->id,
                        'department_id' => $deptData['department_id'],
                        'cost' => $deptData['cost'],
                        'is_roster' => ($deptData['cost_type'] ?? 'hour') === 'day',
                    ]);
                }
            }
        }

        return redirect()->route('admin.staff.show')->with('success', 'Empleado creado exitosamente.');
    }

    public function deactivate($id)
    {
        $staff = Staff::findOrFail($id);

        $userCrewId = auth()->user()->crew_id;
        if ($userCrewId != 1 && $staff->crew_id != $userCrewId) {
            abort(403);
        }

        $staff->isActive = false;
        $staff->save();

        return redirect()->route('admin.staff.show')->with('success', 'Empleado eliminado correctamente.');
    }

    public function edit($id)
    {
        $staff = Staff::with('departmentCosts')->findOrFail($id);

        $userCrewId = auth()->user()->crew_id;
        if ($userCrewId != 1 && $staff->crew_id != $userCrewId) {
            abort(403);
        }

        $departments = Department::where('is_active', true)->get();
        return view('admin.rosters.staff.edit', compact('staff', 'departments'));
    }

    public function editSchedule()
    {
        return view('admin.rosters.schedule.edit');
    }

    public function update(StaffRequest $request, $id)
    {
        $staff = Staff::findOrFail($id);

        $userCrewId = auth()->user()->crew_id;
        if ($userCrewId != 1 && $staff->crew_id != $userCrewId) {
            abort(403);
        }

        $data = $request->validated();

        $staff->update(
            collect($data)->except(['name', 'surnames', 'departments'])->toArray()
        );

        $staff->departmentCosts()->delete();

        if ($request->has('departments')) {
            foreach ($request->input('departments') as $deptData) {
                if (!empty($deptData['department_id']) && isset($deptData['cost'])) {
                    StaffDepartmentCost::create([
                        'staff_id' => $staff->id,
                        'department_id' => $deptData['department_id'],
                        'cost' => $deptData['cost'],
                        'is_roster' => ($deptData['cost_type'] ?? 'hour') === 'day',
                    ]);
                }
            }
        }

        return redirect()->route('admin.staff.show')->with('success', 'Empleado actualizado correctamente.');
    }

    public function rosters(Request $request)
    {
        $data = $this->calculatePayroll($request);

        $adjustmentDefinitions = \App\Models\AdjustmentDefinition::all();
        $departments = Department::where('is_active', true)->get()->keyBy('id');

        return view('admin.rosters.rosters.panel', array_merge($data, compact(
            'adjustmentDefinitions',
            'departments'
        )));
    }

    public function payrollReport(Request $request)
    {
        return $this->buildReport($request, true, 'generatePayrollReport');
    }

    public function feeCheck(Request $request)
    {
        return $this->buildReport($request, false, 'generateFeeCheck');
    }

    private function buildReport(Request $request, bool $rosterFilter, string $pdfMethod)
    {
        $data = $this->calculatePayroll($request);

        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $period = $request->input('period', '8-22');

        $crewsReport = [];
        $grandTotalHours = 0;
        $grandTotalCost = 0;

        foreach ($data['staffGrouped'] as $crewId => $staffList) {
            $crew = $data['allCrews']->firstWhere('id', $crewId);
            $rows = [];
            $crewHours = 0;
            $crewCost = 0;

            foreach ($staffList as $staff) {
                $staffDeptCosts = $data['allDepartmentCosts']->get($staff->id, collect());
                $hasRoster = $staffDeptCosts->contains('is_roster', true);

                if ($hasRoster !== $rosterFilter) {
                    continue;
                }

                $staffAssignments = $data['assignments']
                    ->where('crew_id', $crewId)
                    ->where('staff_id', $staff->id);

                $hours = $staffAssignments->sum('hours');
                $baseCost = $data['totalCostByStaff'][$staff->id] ?? 0;

                $adjustments = $staff->filtered_adjustments ?? collect();
                $perceptionsSum = 0;
                $deductionsSum = 0;
                foreach ($adjustments as $adj) {
                    if ($adj->adjustmentDefinition->type === 'perception') {
                        $perceptionsSum += $adj->amount;
                    } else {
                        $deductionsSum += $adj->amount;
                    }
                }

                $netPay = $baseCost + $perceptionsSum - $deductionsSum;

                $rows[] = [
                    'name' => $staff->name . ' ' . $staff->surnames,
                    'position' => $staff->position ?? '-',
                    'type' => $hasRoster ? 'Planilla' : 'Horas',
                    'hours' => $hours,
                    'baseCost' => $baseCost,
                    'perceptions' => $perceptionsSum,
                    'deductions' => $deductionsSum,
                    'netPay' => $netPay,
                ];

                $crewHours += $hours;
                $crewCost += $netPay;
            }

            if (!empty($rows)) {
                $crewsReport[] = [
                    'crew' => $crew,
                    'rows' => $rows,
                    'totalHours' => $crewHours,
                    'totalCost' => $crewCost,
                ];
            }

            $grandTotalHours += $crewHours;
            $grandTotalCost += $crewCost;
        }

        $reportData = [
            'crewsReport' => $crewsReport,
            'year' => $year,
            'month' => $month,
            'period' => $period,
            'periodDays' => $data['periodDays'],
            'totalHours' => $grandTotalHours,
            'totalCost' => $grandTotalCost,
        ];

        return PdfController::$pdfMethod($reportData);
    }

    private function calculatePayroll(Request $request)
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

        $hourAssignments = HourAssignment::with(['staff', 'staff.departmentCosts', 'department'])
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

        $allDepartmentCosts = StaffDepartmentCost::all()->groupBy('staff_id');

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

            $rosterStaffIds = StaffDepartmentCost::where('is_roster', true)->pluck('staff_id')->unique();
            $rosterStaff = Staff::where('crew_id', $crew->id)
                ->where('isActive', true)
                ->whereIn('id', $rosterStaffIds)
                ->with('departmentCosts')
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
                $staffAssignments = $hourAssignments
                    ->where('crew_id', $crew->id)
                    ->where('staff_id', $staff->id);

                $hours = $staffAssignments->sum('hours');
                $totalHoursByStaff[$staff->id] = (float) number_format($hours, 2, '.', '');

                $staffDeptCosts = $allDepartmentCosts->get($staff->id, collect());

                $staffCost = 0.0;
                $rosterCost = 0.0;

                foreach ($staffDeptCosts->where('is_roster', true) as $deptCost) {
                    $rosterCost += $deptCost->cost * $periodDays;
                }

                foreach ($staffAssignments as $assignment) {
                    $deptCost = $staffDeptCosts->firstWhere('department_id', $assignment->department_id);
                    if ($deptCost && !$deptCost->is_roster) {
                        $staffCost += $deptCost->cost * $assignment->hours;
                    }
                }

                $staffCost += $rosterCost;

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
                $baseCost = $totalCostByStaff[$staff->id] ?? 0;

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

        return compact(
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
            'adjustedTotalCost',
            'allDepartmentCosts'
        );
    }
}
