@extends('layout.mainLayout')
@section('title', 'Nuevo ticket de servicio')

@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Nuevo ticket de servicio</h1>
        <p class="dashboard-subtitle">Describe el problema, asigna prioridad y agrega evidencia si aplica.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="background: #fee2e2; border: 1px solid #991b1b; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{!! nl2br(e($error)) !!}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tickets.save') }}" method="POST" enctype="multipart/form-data" onsubmit="showLoader(true)">
        @csrf

        <div class="modern-card">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 15V6C21 4.89543 20.1046 4 19 4H5C3.89543 4 3 4.89543 3 6V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M21 15L16 20H8L3 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7 8H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M7 12H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <h2>Detalle del ticket</h2>
                </div>
            </div>

            <div style="padding: 24px;">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <div class="modern-field">
                            <label for="title">T&iacute;tulo del ticket</label>
                            <input type="text" name="title" id="title" class="form-control modern-input" value="{{ old('title') }}" required>
                        </div>
                        @error('title')
                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="modern-field">
                            <label for="priority">Prioridad</label>
                            <select name="priority" id="priority" class="form-select modern-input" required>
                                <option value="baja" {{ old('priority') == 'baja' ? 'selected' : '' }}>Baja</option>
                                <option value="media" {{ old('priority') == 'media' ? 'selected' : '' }}>Media</option>
                                <option value="alta" {{ old('priority') == 'alta' ? 'selected' : '' }}>Alta</option>
                                <option value="critica" {{ old('priority') == 'critica' ? 'selected' : '' }}>Cr&iacute;tica</option>
                            </select>
                        </div>
                        @error('priority')
                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="modern-field">
                            <label for="description">Descripci&oacute;n del problema (opcional)</label>
                            <textarea name="description" id="description" rows="4" class="form-control modern-textarea">{{ old('description') }}</textarea>
                        </div>
                        @error('description')
                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="modern-field">
                            <label for="category_id">Categor&iacute;a</label>
                            <select name="category_id" id="category_id" class="form-select modern-input">
                                <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Selecciona una categor&iacute;a</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('category_id')
                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="modern-field">
                            <label for="images">Im&aacute;genes (opcional - m&aacute;ximo 5)</label>
                            <div class="file-input">
                                <input type="file" name="images[]" id="images" class="file-input-native" accept="image/*" multiple>
                                <span class="file-input-icon" aria-hidden="true">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 16V6M12 6L8 10M12 6L16 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </span>
                                <span class="file-input-text" data-default="Sin archivos seleccionados">Sin archivos seleccionados</span>
                            </div>
                            <small class="text-muted" style="display: block; margin-top: 6px;">Puedes seleccionar hasta 5 im&aacute;genes. Formatos: JPG, PNG, GIF, WEBP (m&aacute;x. 5MB cada una)</small>
                        </div>
                        @error('images')
                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                {{ $message }}
                            </div>
                        @enderror
                        @error('images.*')
                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: center; gap: 12px; margin-top: 32px; margin-bottom: 60px;">
            <button type="submit" class="btn-modern btn-primary" style="min-width: 200px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 12L9 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M21 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M21 12L15 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M9 6H6C4.34315 6 3 7.34315 3 9V15C3 16.6569 4.34315 18 6 18H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Enviar ticket
            </button>
            <a href="{{ route('tickets.list') }}" class="btn-modern" style="min-width: 200px; background: white; color: #6b7280; border: 1px solid #e5e7eb;" onclick="showLoader(true)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Cancelar
            </a>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.file-input-native').forEach((input) => {
        const wrapper = input.closest('.file-input');
        const text = wrapper?.querySelector('.file-input-text');
        if (!text) {
            return;
        }
        const defaultText = text.dataset.default || text.textContent;
        const updateText = () => {
            const count = input.files ? input.files.length : 0;
            if (!count) {
                text.textContent = defaultText;
                return;
            }
            if (count === 1) {
                text.textContent = input.files[0].name;
                return;
            }
            text.textContent = `${count} archivos seleccionados`;
        };
        input.addEventListener('change', updateText);
        updateText();
    });
</script>
@endpush
