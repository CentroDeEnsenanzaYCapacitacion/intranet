@extends('layout.mainLayout')
@section('title', 'Modificar costo')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Modificar Costo</h1>
        <p class="dashboard-subtitle">Actualiza el monto del costo seleccionado</p>
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

    @if (session('success'))
        <div id="success" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-left: 4px solid #10b981; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="modern-card" style="margin-bottom: 24px;">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M18.5 2.50001C18.8978 2.10219 19.4374 1.87869 20 1.87869C20.5626 1.87869 21.1022 2.10219 21.5 2.50001C21.8978 2.89784 22.1213 3.4374 22.1213 4.00001C22.1213 4.56262 21.8978 5.10219 21.5 5.50001L12 15L8 16L9 12L18.5 2.50001Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Información del Costo</h2>
            </div>
        </div>

        <div style="padding: 24px;">
            <div style="text-align: center; margin-bottom: 32px;">
                <h3 style="color: #1f2937; font-size: 1.5rem; font-weight: 600; margin-bottom: 8px;">
                    {{ $amount->receiptType->name }}
                    @if ($amount->course)
                        {{ $amount->course->name }}
                    @endif
                    @if ($amount->crew_id != 1)
                        {{ $amount->crew->name }}
                    @endif
                </h3>
            </div>

            <form action="{{ route('admin.catalogues.amount.update', ['id' => $amount->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="max-width: 500px; margin: 0 auto;">
                    <div class="modern-field">
                        <label for="amount" class="modern-label">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                                <path d="M12 2V22M17 5H9.5C8.57174 5 7.6815 5.36875 7.02513 6.02513C6.36875 6.6815 6 7.57174 6 8.5C6 9.42826 6.36875 10.3185 7.02513 10.9749C7.6815 11.6313 8.57174 12 9.5 12H14.5C15.4283 12 16.3185 12.3687 16.9749 13.0251C17.6313 13.6815 18 14.5717 18 15.5C18 16.4283 17.6313 17.3185 16.9749 17.9749C16.3185 18.6313 15.4283 19 14.5 19H6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Monto
                        </label>
                        <input
                            id="amount"
                            class="modern-input"
                            name="amount"
                            type="text"
                            value="{{ old('amount', $amount->amount) }}"
                            placeholder="Ingrese el monto"
                            required>
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: center; margin-top: 32px;">
                        <button class="btn-modern btn-primary" type="submit">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 2.58579C3.96086 2.21071 4.46957 2 5 2H16L21 7V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17 21V13H7V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7 3V7H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Guardar Cambios
                        </button>
                        <a href="{{ route('admin.catalogues.amounts') }}" class="btn-modern btn-secondary">
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
@endsection
