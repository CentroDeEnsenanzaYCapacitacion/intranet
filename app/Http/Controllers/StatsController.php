<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:1,2');
    }

    public function reports(Request $request, $period, $year = null)
    {
        $latestReport = Report::orderBy('created_at', 'desc')->first();
        $defaultYear = $latestReport ? Carbon::parse($latestReport->created_at)->year : Carbon::now()->year;
        $year = $year ?? $defaultYear;

        $month = $request->input('month');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($period == 'mensual' && !$month && !$startDate && !$endDate) {
            return redirect()->route('admin.stats.reports', ['period' => 'mensual', 'year' => $year, 'month' => Carbon::now()->month]);
        }

        if ($period == 'semestral' && !$month && !$startDate && !$endDate) {
            $currentMonth = Carbon::now()->month;
            $defaultSemester = $currentMonth <= 6 ? 1 : 7;
            return redirect()->route('admin.stats.reports', ['period' => 'semestral', 'year' => $year, 'month' => $defaultSemester]);
        }

        $yearExpression = DB::connection()->getDriverName() === 'sqlite'
            ? "strftime('%Y', created_at)"
            : 'YEAR(created_at)';

        $availableYears = Report::selectRaw($yearExpression . ' as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $stats = [];
        $compareStats = [];
        $userStats = [];

        $isCustomRange = !empty($startDate) && !empty($endDate);

        if ($period == 'mensual') {
            $totalReports = Report::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $enrolledReports = Report::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereHas('receipts')
                ->count();

            $stats[] = [
                'period' => Carbon::create($year, $month, 1)->format('M'),
                'reports' => $totalReports,
                'enrolled' => $enrolledReports,
                'conversion_rate' => $totalReports > 0 ? round(($enrolledReports / $totalReports) * 100, 2) : 0
            ];

            $prevYearReports = Report::whereYear('created_at', $year - 1)
                ->whereMonth('created_at', $month)
                ->count();

            $prevYearEnrolled = Report::whereYear('created_at', $year - 1)
                ->whereMonth('created_at', $month)
                ->whereHas('receipts')
                ->count();

            $compareStats[] = [
                'period' => Carbon::create($year, $month, 1)->format('M'),
                'reports' => $prevYearReports,
                'enrolled' => $prevYearEnrolled,
            ];
        } elseif ($period == 'semestral') {
            $startMonth = $month == 1 ? 1 : 7;
            $endMonth = $month == 1 ? 6 : 12;

            $totalReports = Report::whereYear('created_at', $year)
                ->whereMonth('created_at', '>=', $startMonth)
                ->whereMonth('created_at', '<=', $endMonth)
                ->count();

            $enrolledReports = Report::whereYear('created_at', $year)
                ->whereMonth('created_at', '>=', $startMonth)
                ->whereMonth('created_at', '<=', $endMonth)
                ->whereHas('receipts')
                ->count();

            $stats[] = [
                'period' => $month == 1 ? 'Ene-Jun' : 'Jul-Dic',
                'reports' => $totalReports,
                'enrolled' => $enrolledReports,
                'conversion_rate' => $totalReports > 0 ? round(($enrolledReports / $totalReports) * 100, 2) : 0
            ];

            $prevYearReports = Report::whereYear('created_at', $year - 1)
                ->whereMonth('created_at', '>=', $startMonth)
                ->whereMonth('created_at', '<=', $endMonth)
                ->count();

            $prevYearEnrolled = Report::whereYear('created_at', $year - 1)
                ->whereMonth('created_at', '>=', $startMonth)
                ->whereMonth('created_at', '<=', $endMonth)
                ->whereHas('receipts')
                ->count();

            $compareStats[] = [
                'period' => $month == 1 ? 'Ene-Jun' : 'Jul-Dic',
                'reports' => $prevYearReports,
                'enrolled' => $prevYearEnrolled,
            ];
        } elseif ($period == 'anual') {
            for ($y = $year - 3; $y <= $year; $y++) {
                $totalReports = Report::whereYear('created_at', $y)->count();
                $enrolledReports = Report::whereYear('created_at', $y)
                    ->whereHas('receipts')
                    ->count();

                $stats[] = [
                    'period' => $y,
                    'reports' => $totalReports,
                    'enrolled' => $enrolledReports,
                    'conversion_rate' => $totalReports > 0 ? round(($enrolledReports / $totalReports) * 100, 2) : 0
                ];
            }
        }

        $baseQuery = function ($query) use ($isCustomRange, $startDate, $endDate, $year, $month) {
            if ($isCustomRange) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            } elseif ($month) {
                $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
            } else {
                $query->whereYear('created_at', $year);
            }
        };

        $userStats = User::where('is_active', true)
            ->withCount([
                'reports as reports_count' => $baseQuery,
                'reports as enrolled_reports_count' => function ($query) use ($baseQuery) {
                    $baseQuery($query);
                    $query->whereHas('receipts');
                }
            ])
            ->get()
            ->filter(function ($user) {
                return $user->reports_count > 0;
            })
            ->sortByDesc('reports_count')
            ->take(10)
            ->values();

        $totalReportsQuery = Report::query();
        $baseQuery($totalReportsQuery);
        $totalReports = $totalReportsQuery->count();

        $totalEnrolledQuery = Report::query();
        $baseQuery($totalEnrolledQuery);
        $totalEnrolled = $totalEnrolledQuery->whereHas('receipts')->count();

        $overallConversion = $totalReports > 0 ? round(($totalEnrolled / $totalReports) * 100, 2) : 0;

        $prevYearTotalReports = Report::whereYear('created_at', $year - 1)->count();
        $prevYearTotalEnrolled = Report::whereYear('created_at', $year - 1)->whereHas('receipts')->count();

        $reportsDiff = $prevYearTotalReports > 0 ? round((($totalReports - $prevYearTotalReports) / $prevYearTotalReports) * 100, 2) : 0;
        $enrolledDiff = $prevYearTotalEnrolled > 0 ? round((($totalEnrolled - $prevYearTotalEnrolled) / $prevYearTotalEnrolled) * 100, 2) : 0;

        $compareStats = $isCustomRange ? [] : $compareStats;

        return view('admin.stats.reports', compact(
            'period',
            'stats',
            'compareStats',
            'userStats',
            'year',
            'availableYears',
            'totalReports',
            'totalEnrolled',
            'overallConversion',
            'prevYearTotalReports',
            'prevYearTotalEnrolled',
            'reportsDiff',
            'enrolledDiff',
            'month',
            'startDate',
            'endDate'
        ));
    }

    public function billing()
    {
        return view('admin.stats.billing');
    }
}
