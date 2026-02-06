@push('styles')
<link href="{{ asset('assets/css/calendar-modal.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('assets/js/modal_utils.js') }}"></script>
<script src="{{ asset('assets/js/password_confirm_modal.js') }}"></script>
@endpush

<div class="custom-modal" id="passwordConfirmModal" style="display:none;">
    <div class="custom-modal-content">
        <span class="custom-modal-close" id="passwordConfirmClose">&times;</span>
        <h5>Confirmar contraseña</h5>
        <p style="color: #6b7280; margin-bottom: 16px;">Por seguridad, confirma tu contraseña para continuar.</p>
        <div class="modern-field">
            <label for="passwordConfirmInput">Contraseña</label>
            <input type="password" class="form-control modern-input" id="passwordConfirmInput" placeholder="Ingresa tu contraseña" autocomplete="current-password">
        </div>
        <div id="passwordConfirmError" style="display:none; color: #991b1b; font-size: 14px; margin-top: 4px; margin-bottom: 12px;"></div>
        <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 16px;">
            <button type="button" class="btn-modern btn-primary" id="passwordConfirmBtn" style="min-width: 140px;">Confirmar</button>
        </div>
    </div>
</div>
