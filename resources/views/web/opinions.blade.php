@extends('layout.mainLayout')
@section('title','Opiniones')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Opiniones</h1>
        <p class="dashboard-subtitle">Publica opiniones de alumnos egresados</p>
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

    <div style="display: flex; justify-content: flex-end; margin-bottom: 24px;">
        <form action="{{ route('web.opinions.add') }}" method="post">
            @csrf
            <button type="submit" class="btn-modern btn-primary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Agregar opinion
            </button>
        </form>
    </div>

    @php($opinionCount = $opinions->count())
    @php($ratingOptions = ['0', '0.5', '1', '1.5', '2', '2.5', '3', '3.5', '4', '4.5', '5'])
    <form id="form" class="opinions-form" action="{{ route('web.opinions.post') }}" method="post" enctype="multipart/form-data">
        @csrf
        @foreach($opinions as $opinion)
            @php($opinionId = $opinion->id)
            <div class="modern-card mb-4">
                    <div class="card-header-modern">
                        <div class="header-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21 15C21 16.6569 19.6569 18 18 18H8L3 21V6C3 4.34315 4.34315 3 6 3H18C19.6569 3 21 4.34315 21 6V15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8 9H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M8 13H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <h2>Opinion {{ $loop->iteration }}</h2>
                        </div>
                        @if($opinionCount > 3)
                            <div class="header-actions">
                                <button type="submit"
                                        class="action-btn action-delete"
                                        title="Eliminar opinion"
                                        aria-label="Eliminar opinion"
                                        formaction="{{ route('web.opinions.delete', $opinionId) }}"
                                        formmethod="post"
                                        formnovalidate>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 20.0391 5 19.5304 5 19V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>

                <div style="padding: 16px;">
                    <div class="row align-items-stretch">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="modern-field" style="position: relative; height: 100%; padding-top: 20px; align-items: center; justify-content: center;">
                                <label style="position: absolute; top: 0; left: 0;">Vista previa</label>
                                <div class="preview-box" style="display: flex; align-items: center; justify-content: center; overflow: hidden; width: 100%; max-width: 250px; height: 290px; margin: 0 auto;">
                                    <img style="max-width: 100%; max-height: 100%; width: auto; height: auto; border-radius: 8px;" src="{{ $opinion->image_url }}" alt="Opinion {{ $loop->iteration }}"/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="modern-field">
                                        <label for="img_{{ $opinionId }}">Nueva imagen *</label>
                                            <div class="file-input">
                                                <input class="file-input-native" type="file" accept="image/jpeg, image/png" name="img[{{ $opinionId }}]" id="img_{{ $opinionId }}" @if(!$opinion->has_image) required @endif>
                                                <span class="file-input-icon" aria-hidden="true">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12 16V6M12 6L8 10M12 6L16 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    </svg>
                                                </span>
                                                <span class="file-input-text" data-default="Sin archivos seleccionados">Sin archivos seleccionados</span>
                                            </div>
                                        </div>
                                        @if($errors->has('img.' . $opinionId))
                                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                                {{ $errors->first('img.' . $opinionId) }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="modern-field">
                                        <label for="name{{ $opinionId }}">Nombre *</label>
                                            <input class="form-control modern-input" type="text" name="name[{{ $opinionId }}]" id="name{{ $opinionId }}" value="{{ old('name.' . $opinionId, $opinion->name) }}" required>
                                        </div>
                                        @if($errors->has('name.' . $opinionId))
                                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                                {{ $errors->first('name.' . $opinionId) }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="modern-field">
                                        <label for="course{{ $opinionId }}">Curso *</label>
                                            <input class="form-control modern-input" type="text" name="course[{{ $opinionId }}]" id="course{{ $opinionId }}" value="{{ old('course.' . $opinionId, $opinion->course) }}" required>
                                        </div>
                                        @if($errors->has('course.' . $opinionId))
                                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                                {{ $errors->first('course.' . $opinionId) }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <div class="modern-field">
                                        <label for="rating{{ $opinionId }}">Valoracion en estrellas *</label>
                                            <select class="form-control modern-input" name="rating[{{ $opinionId }}]" id="rating{{ $opinionId }}" required>
                                                @foreach($ratingOptions as $ratingOption)
                                                    <option value="{{ $ratingOption }}" @if((string) old('rating.' . $opinionId, $opinion->rating) === (string) $ratingOption) selected @endif>
                                                        {{ $ratingOption }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if($errors->has('rating.' . $opinionId))
                                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                                {{ $errors->first('rating.' . $opinionId) }}
                                            </div>
                                        @endif
                                    </div>

                                <div class="col-md-12 mb-2">
                                    <div class="modern-field">
                                        <label for="description{{ $opinionId }}">Descripcion *</label>
                                        <textarea class="form-control modern-textarea" name="description[{{ $opinionId }}]" id="description{{ $opinionId }}" rows="4" maxlength="250" required>{{ old('description.' . $opinionId, $opinion->description) }}</textarea>
                                        </div>
                                        @if($errors->has('description.' . $opinionId))
                                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                                {{ $errors->first('description.' . $opinionId) }}
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
            <button type="submit" class="btn-modern btn-primary" style="min-width: 200px;">
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
<script src="{{ asset('assets/js/file_input.js') }}"></script>
@endpush
