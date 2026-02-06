@extends('layout.mainLayout')
@section('title', 'Nuevo Costo')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Nuevo Costo</h1>
        <p class="dashboard-subtitle">Añade un nuevo costo al sistema</p>
    </div>

    @if ($errors->any())
        <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #ef4444; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="modern-card" style="margin-bottom: 24px;">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Información del Costo</h2>
            </div>
        </div>

        <div style="padding: 24px;">
            <form action="{{ route('admin.catalogues.amount.store') }}" method="POST" data-password-confirm>
                @csrf

                <div style="max-width: 600px; margin: 0 auto;">
                    <div class="modern-field">
                        <label for="name" class="modern-label">Nombre</label>
                        <input
                            id="name"
                            class="modern-input"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            placeholder="Ej: Credencial, Certificado, Material"
                            required>
                    </div>

                    <div class="modern-field">
                        <label for="amount" class="modern-label">Monto</label>
                        <input
                            id="amount"
                            class="modern-input"
                            name="amount"
                            type="text"
                            value="{{ old('amount') }}"
                            placeholder="Ej: 150.00"
                            required>
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: center; margin-top: 32px;">
                        <button class="btn-modern btn-primary" type="submit">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 2.58579C3.96086 2.21071 4.46957 2 5 2H16L21 7V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17 21V13H7V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7 3V7H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Guardar
                        </button>
                        <a href="{{ route('admin.catalogues.amounts.show') }}" class="btn-modern btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 12H5M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('includes.password-confirm-modal')
@endsection
