@extends('layout.mainLayout')
@section('title','Nuevo Vale')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Nuevo Vale</h1>
        <p class="dashboard-subtitle">Emite un vale para otros conceptos</p>
    </div>

    @if(session('success'))
        <div id="success" class="alert alert-success" style="background: #d1fae5; border: 1px solid #065f46; color: #065f46; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div id="error" class="alert alert-danger" style="background: #fee2e2; border: 1px solid #991b1b; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="modern-card">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Datos del Vale</h2>
            </div>
        </div>

        <div style="padding: 24px;">
            <form id="paybillForm" action="{{ route('system.collection.paybill-post') }}" method="POST" data-password-confirm>
                @csrf
                <input type="hidden" value="{{ Auth::user()->crew_id }}" name="crew_id">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="user_id" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">Autoriza</label>
                        <select class="form-control text-uppercase" name="user_id" id="user_id" required style="height: 48px; border: 2px solid #e5e7eb; border-radius: 12px;">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} {{ $user->surnames }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="receives" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">Recibe</label>
                        <input class="form-control text-uppercase" name="receives" id="receives" type="text" required style="height: 48px; border: 2px solid #e5e7eb; border-radius: 12px; padding: 0 16px;">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="concept" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px;">Concepto</label>
                        <input class="form-control text-uppercase" name="concept" id="concept" type="text" required style="height: 48px; border: 2px solid #e5e7eb; border-radius: 12px; padding: 0 16px;">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="receipt_amount" style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 8px; text-align: center;">Importe</label>
                        <div style="max-width: 400px; margin: 0 auto;">
                            <div class="input-group">
                                <span class="input-group-text" style="background: linear-gradient(135deg, #F57F17 0%, #F9A825 100%); color: white; font-weight: 600; border: none; font-size: 18px;">$</span>
                                <input class="form-control" type="number" step="0.01" min="0.01" name="amount" id="receipt_amount" placeholder="0.00" required style="font-size: 18px; font-weight: 600; text-align: center; height: 56px; border: 2px solid #e5e7eb; border-left: none;">
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: center; gap: 12px; margin-top: 32px; margin-bottom: 24px;">
                    <a href="{{ route('system.collection.paybills') }}" class="btn-modern btn-primary" style="min-width: 150px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit" class="btn-modern btn-primary" style="min-width: 200px;" onclick="showLoader(true)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Emitir Vale
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('includes.password-confirm-modal')
@endsection
@push('scripts')
<script src="{{ asset('assets/js/xss-protection.js') }}"></script>
<script src="{{ asset('assets/js/paybill_redirection.js') }}"></script>
@endpush
