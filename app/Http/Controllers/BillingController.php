<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Crew;
use App\Models\ReceiptType;
use App\Models\PaymentType;
use App\Models\Paybill;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $crews = Crew::all();
        $receiptTypes = ReceiptType::all();
        $paymentTypes = PaymentType::all();

        // Determinar el plantel según el rol
        $plantelId = null;
        if ($user->role_id == 2) {
            // Rol 2: solo puede ver su propio plantel
            $plantelId = $user->crew_id;
        } elseif ($user->role_id == 1 && $request->filled('plantel')) {
            // Rol 1: puede seleccionar cualquier plantel
            $plantelId = intval($request->plantel);
        }

        // 🗓 Obtener el rango de fechas (puede ser null)
        $rangoFechas = $this->getFechaRango($request);

        // 📄 Recibos con todos los filtros
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

        // 💸 Gastos solo con filtro de fecha y plantel
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

        // Si es "histórico" o no se seleccionó nada válido, no se aplica filtro
        return null;
    }
}
