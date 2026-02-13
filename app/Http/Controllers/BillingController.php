<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Crew;
use App\Models\ReceiptType;
use App\Models\PaymentType;
use App\Models\Paybill;
use App\Models\SysRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:1,2');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $crews = Crew::all();
        $receiptTypes = ReceiptType::all();
        $paymentTypes = PaymentType::all();

        $plantelId = null;
        if ($user->role_id == 2) {

            $plantelId = $user->crew_id;
        } elseif ($user->role_id == 1 && $request->filled('plantel')) {

            $selectedPlantel = intval($request->plantel);
            if ($selectedPlantel !== 1) {
                $plantelId = $selectedPlantel;
            }

        }

        $rangoFechas = $this->getFechaRango($request);

        $receipts = Receipt::with(['receiptType', 'payment', 'student'])
            ->when($rangoFechas, function ($query) use ($rangoFechas) {
                $query->whereBetween('created_at', $rangoFechas);
            })
            ->when($plantelId, function ($query) use ($plantelId) {
                $query->where('crew_id', $plantelId);
            })
            ->when($request->filled('tipo_recibo'), function ($query) use ($request) {
                $query->where('receipt_type_id', intval($request->tipo_recibo));
            })
            ->when($request->filled('tipo_pago'), function ($query) use ($request) {
                $query->where('payment_type_id', intval($request->tipo_pago));
            })
            ->get();

        $paybills = Paybill::with('crew')
            ->when($rangoFechas, function ($query) use ($rangoFechas) {
                $query->whereBetween('created_at', $rangoFechas);
            })
            ->when($plantelId, function ($query) use ($plantelId) {
                $query->where('crew_id', $plantelId);
            })
            ->get();

        $porTipoPago = $receipts->groupBy('payment_type_id')->map(function ($group) {
            return $group->sum('amount');
        });
        $tiposPagoConTotales = PaymentType::whereIn('id', $porTipoPago->keys())
    ->get()
    ->map(function ($tipo) use ($porTipoPago) {
        return [
            'nombre' => $tipo->name,
            'total' => $porTipoPago[$tipo->id] ?? 0
        ];
    });

        $receiptsTotal = $receipts->sum('amount');
        $paybillsTotal = $paybills->sum('amount');
        $diferenciaTotal = $receiptsTotal - $paybillsTotal;

        return view('admin.stats.billing', compact(
            'crews',
            'receiptTypes',
            'paymentTypes',
            'receipts',
            'paybills',
            'receiptsTotal',
            'paybillsTotal',
            'diferenciaTotal',
            'tiposPagoConTotales'
        ));

    }

    public function requestAmountChange(Request $request)
    {
        $request->validate([
            'type' => 'required|in:receipt,paybill',
            'item_id' => 'required|integer',
            'new_amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:500'
        ]);

        $field = $request->type === 'receipt' ? 'receipt_id' : 'paybill_id';

        $existingRequest = SysRequest::where($field, $request->item_id)
            ->where('request_type_id', 4)
            ->whereNull('approved')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'Ya existe una solicitud de cambio de importe pendiente para este registro.');
        }

        if ($request->type === 'receipt') {
            $item = Receipt::findOrFail($request->item_id);
            $label = 'Recibo #' . $item->id;
        } else {
            $item = Paybill::findOrFail($request->item_id);
            $label = 'Vale #' . $item->id;
        }

        $user = Auth::user();
        if ($user->role_id != 1 && $item->crew_id != $user->crew_id) {
            abort(403);
        }

        SysRequest::create([
            'request_type_id' => 4,
            'description' => $label . ' | Importe actual: $' . number_format($item->amount, 2) . ' | Nuevo importe: $' . number_format($request->new_amount, 2) . ' | ' . $request->reason,
            'user_id' => Auth::id(),
            $field => $request->item_id
        ]);

        return back()->with('success', 'Solicitud de cambio de importe enviada correctamente.');
    }

    private function getFechaRango(Request $request)
    {
        switch ($request->fecha) {
            case 'hoy':
                return [now()->startOfDay(), now()->endOfDay()];
            case 'semana':
                return [now()->subWeek(), now()];
            case 'mes':
                return [now()->subMonth(), now()];
            case 'personalizado':
                if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
                    return [
                        Carbon::parse($request->fecha_inicio)->startOfDay(),
                        Carbon::parse($request->fecha_fin)->endOfDay()
                    ];
                } elseif ($request->filled('fecha_inicio')) {
                    return [
                        Carbon::parse($request->fecha_inicio)->startOfDay(),
                        Carbon::parse($request->fecha_inicio)->endOfDay()
                    ];
                }
                break;
        }

        return null;
    }
}
