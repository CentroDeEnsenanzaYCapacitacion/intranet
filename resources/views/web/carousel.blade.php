@extends('layout.mainLayout')
@section('title','Carrusel Web')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Carrusel Inicial</h1>
        <p class="dashboard-subtitle">Administra las im&aacute;genes y contenido del carrusel de la p&aacute;gina web</p>
    </div>

    @if(session('success'))
        <div id="success" class="alert alert-success" style="background: #d1fae5; border: 1px solid #065f46; color: #065f46; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" style="background: #fee2e2; border: 1px solid #991b1b; color: #991b1b; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="form" class="carousel-form" action="{{ route('web.carousel.post') }}" method="post" enctype="multipart/form-data">
        @csrf

        @foreach($carousels as $index => $carousel)
            <div class="modern-card mb-4">
                <div class="card-header-modern">
                    <div class="header-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="3" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5" fill="currentColor"/>
                            <path d="M21 15L16 10L5 21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h2>Imagen {{ $index + 1 }}</h2>
                    </div>
                </div>

                <div style="padding: 24px;">
                    <div class="row align-items-center">
                        <div class="col-md-4 mb-4">
                            <div class="modern-field">
                                <label>Vista previa</label>
                                <div class="preview-box">
                                    <img style="width: 100%; border-radius: 8px;" src="{{ asset('assets/img/carousel/' . ($index + 1) . '.jpg') . '?v=' . $carousel->updated_at->timestamp }}" onerror="replace_image(this);" alt="Imagen {{ $index + 1 }}"/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="modern-field">
                                        <label for="img_{{ $index + 1 }}">Nueva imagen</label>
                                        <div class="file-input">
                                            <input class="file-input-native" type="file" accept="image/jpeg, image/png" name="img_{{ $index + 1 }}" id="img_{{ $index + 1 }}">
                                            <span class="file-input-icon" aria-hidden="true">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 16V6M12 6L8 10M12 6L16 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                </svg>
                                            </span>
                                            <span class="file-input-text" data-default="Sin archivos seleccionados">Sin archivos seleccionados</span>
                                        </div>
                                    </div>
                                    @if($errors->has('img_' . ($index + 1)))
                                        <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                            {{ $errors->first('img_' . ($index + 1)) }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="modern-field">
                                        <label for="title{{ $index + 1 }}">T&iacute;tulo</label>
                                        <input class="form-control modern-input" type="text" name="title{{ $index + 1 }}" id="title{{ $index + 1 }}" value="{{ old('title' . ($index + 1), $carousel->title) }}">
                                    </div>
                                    @if($errors->has('title' . ($index + 1)))
                                        <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                            {{ $errors->first('title' . ($index + 1)) }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="modern-field">
                                        <label for="description{{ $index + 1 }}">Descripci&oacute;n</label>
                                        <textarea class="form-control modern-textarea" name="description{{ $index + 1 }}" id="description{{ $index + 1 }}" rows="5">{{ old('description' . ($index + 1), $carousel->description) }}</textarea>
                                    </div>
                                    @if($errors->has('description' . ($index + 1)))
                                        <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                            {{ $errors->first('description' . ($index + 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div style="display: flex; justify-content: center; margin-bottom: 32px;">
            <button type="submit" class="btn-modern btn-primary" style="min-width: 200px;" onclick="showLoader(true)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16L21 8V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 21V13H7V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 3V8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Guardar Cambios
            </button>
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
            const name = input.files && input.files.length ? input.files[0].name : defaultText;
            text.textContent = name;
        };
        input.addEventListener('change', updateText);
        updateText();
    });
</script>
@endpush

