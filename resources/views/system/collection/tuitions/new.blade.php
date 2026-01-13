@extends('layout.mainLayout')
@section('title', 'Emisión de recibo')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Emisi&oacute;n de recibo</h1>
        <p class="dashboard-subtitle">Genera el recibo de colegiatura y configura los datos de pago.</p>
    </div>

    @if (session('success'))
        <div id="success" class="alert alert-success" style="background: #d1fae5; border: 1px solid #065f46; color: #065f46; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div id="error" class="alert alert-danger" style="background: #fee2e2; border: 1px solid #991b1b; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            {{ session('error') }}
        </div>
    @endif

    <form id="newTuitionForm" action="{{ route('system.collection.tuitions.receipt-post') }}" method="POST">
        @csrf
        <input type="hidden" value="{{ $student->crew_id }}" name="crew_id">
        <input type="hidden" value="{{ $student->id }}" name="student_id">
        <input type="hidden" name="concept" id="conceptHidden">
        <input type="hidden" name="amount" id="amountHidden">

        <div class="modern-card">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <h2>Datos del recibo</h2>
                </div>
            </div>

            <div style="padding: 24px;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="modern-field">
                            <label for="receipt_type_id">Tipo de recibo</label>
                            <select class="form-select modern-input text-uppercase" name="receipt_type_id" id="receipt_type_id">
                                @foreach ($receipt_types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3" id="attr_group">
                        <div class="modern-field">
                            <label for="attr_id">Atributos</label>
                            <select class="form-select modern-input text-uppercase" name="attr_id" id="attr_id">
                                @foreach ($receipt_attributes as $attribute)
                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 8px;">
                    <div class="col-md-6 mb-3">
                        <div class="modern-field">
                            <label>Concepto</label>
                            <div id="conceptDiv" style="height: 48px; padding: 0 16px; display: flex; align-items: center; border: 2px solid #e5e7eb; border-radius: 12px; background: #f9fafb; font-size: 14px; color: #1a1a1a;"></div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="modern-field">
                            <label>Importe</label>
                            <div style="width: 100%; max-width: 320px; min-width: 240px;">
                                <input id="amountDiv" type="text" class="form-control modern-input" style="text-align: left; background: #f9fafb; width: 100%;" readonly>
                                <input class="form-control modern-input" style="text-align: left; display: none; height: 48px; width: 100%;" type="text" name="receipt_amount" id="receipt_amount" placeholder="Ingrese el monto">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modern-card">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 7H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M5 17H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <h2>Opciones de pago</h2>
                </div>
            </div>

            <div style="padding: 24px;">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div style="padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; background: #ffffff;">
                            <div class="form-check" style="margin: 0;">
                                <input class="form-check-input" name="card_payment" type="checkbox" value="card" id="cardCheck">
                                <label class="form-check-label" for="cardCheck">
                                    <b>Tarjeta/Dep&oacute;sito</b>
                                </label>
                            </div>
                            <div id="voucher" style="display: none; margin-top: 12px;">
                                <div class="modern-field">
                                    <label for="voucher_input">Voucher</label>
                                    <input id="voucher_input" class="form-control modern-input text-uppercase" name="voucher" type="text">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div style="padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; background: #ffffff;">
                            <div class="form-check" style="margin: 0;">
                                <input class="form-check-input" name="bill" type="checkbox" value="bill" id="billCheck">
                                <label class="form-check-label" for="billCheck">
                                    <b>Factura</b>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 8px;">
                    <div class="col-md-6 mb-3" id="surchargeContainer" style="display: none;">
                        <div style="padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; background: #ffffff;">
                            <div class="form-check" style="margin: 0;">
                                <input class="form-check-input" name="apply_surcharge" type="checkbox" value="1" id="surchargeCheck">
                                <label class="form-check-label" for="surchargeCheck">
                                    <b>Aplicar recargo</b>
                                </label>
                            </div>
                            <div id="surchargeOptions" style="display: none; margin-top: 12px;">
                                <div class="modern-field">
                                    <label for="surchargePercentage">Porcentaje de recargo</label>
                                    <select name="surcharge_percentage" id="surchargePercentage" class="form-select modern-input">
                                        <option value="5">5%</option>
                                        <option value="10">10%</option>
                                        <option value="15">15%</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3" id="earlyDiscountContainer" style="display: none;">
                        <div style="padding: 16px; border: 2px solid #e5e7eb; border-radius: 12px; background: #ffffff;">
                            <div class="form-check" style="margin: 0;">
                                <input class="form-check-input" name="apply_early_discount" type="checkbox" value="1" id="earlyDiscountCheck">
                                <label class="form-check-label" for="earlyDiscountCheck">
                                    <b>Aplicar descuento por pronto pago</b>
                                </label>
                            </div>
                            <div id="earlyDiscountOptions" style="display: none; margin-top: 12px;">
                                <div class="modern-field">
                                    <label for="earlyDiscountPercentage">Porcentaje de descuento</label>
                                    <div style="display: flex; align-items: center; gap: 8px; max-width: 160px;">
                                        <input type="number" name="early_discount_percentage" id="earlyDiscountPercentage" class="form-control modern-input" min="1" max="10" value="5">
                                        <span style="font-size: 14px; color: #6b7280;">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: center; gap: 12px; margin-top: 32px; margin-bottom: 60px;">
            <button class="btn-modern btn-primary" type="submit" onclick="showLoader(true)" style="min-width: 200px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16L21 8V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 21V13H7V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 3V8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Emitir recibo
            </button>
        </div>
    </form>

    <script>
        var student = @json($student);
        var course = @json($course);
        var crew_course_amounts = @json($crew_course_amounts);
        var general_amounts = @json($general_amounts);
        var amounts = crew_course_amounts.concat(general_amounts);
        var receipt_attributes = @json($receipt_attributes);
        var student_tuition_receipts = @json($student_tuition_receipts);
    </script>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/xss-protection.js') }}"></script>
<script src="{{ asset('assets/js/new_tuition.js') }}"></script>
<script src="{{ asset('assets/js/new_tuition_redirection.js') }}"></script>
@endpush
