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
            <form action="{{ route('admin.catalogues.amount.store') }}" method="POST">
                @csrf

                <div style="max-width: 600px; margin: 0 auto;">
                    <div class="modern-field">
                        <label for="crew_id" class="modern-label">Plantel</label>
                        <select id="crew_id" name="crew_id" class="modern-input" required>
                            <option value="">Seleccione un plantel</option>
                            <option value="1" {{ old('crew_id') == 1 ? 'selected' : '' }}>TODOS LOS PLANTELES</option>
                            @foreach ($crews as $crew)
                                <option value="{{ $crew->id }}" {{ old('crew_id') == $crew->id ? 'selected' : '' }}>
                                    {{ $crew->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modern-field">
                        <label for="course_id" class="modern-label">Curso</label>
                        <select id="course_id" name="course_id" class="modern-input" required>
                            <option value="">Seleccione un curso</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="modern-field">
                        <label for="receipt_type_id" class="modern-label">Tipo de Recibo</label>
                        <select id="receipt_type_id" name="receipt_type_id" class="modern-input" required>
                            <option value="">Seleccione un tipo</option>
                            @foreach ($receiptTypes as $type)
                                <option value="{{ $type->id }}" {{ old('receipt_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

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
                            value="{{ old('amount') }}"
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
                            Guardar Costo
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
@endsection
