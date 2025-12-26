@extends('layout.mainLayout')
@section('title','Modificar curso')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Modificar Curso</h1>
        <p class="dashboard-subtitle">Actualiza la información del curso seleccionado</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-left: 4px solid #ef4444; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{!! nl2br(e($error)) !!}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.catalogues.courses.update', ['id' => $course->id]) }}" method="post" onsubmit="showLoader(true)">
        @csrf
        @method('PUT')

        <div class="modern-card" style="margin-bottom: 24px;">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M18.5 2.50001C18.8978 2.10219 19.4374 1.87869 20 1.87869C20.5626 1.87869 21.1022 2.10219 21.5 2.50001C21.8978 2.89784 22.1213 3.4374 22.1213 4.00001C22.1213 4.56262 21.8978 5.10219 21.5 5.50001L12 15L8 16L9 12L18.5 2.50001Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>Información del Curso</h2>
                </div>
            </div>

            <div style="padding: 24px;">
                <div class="row">
                    <div class="col-md-7 mb-3">
                        <div class="modern-field">
                            <label for="name">Nombre del curso</label>
                            <input type="text" class="modern-input text-uppercase" id="name" placeholder="Nombre del curso" name="name" value="{{ old('name', $course->name) }}">
                            <span class="error-message" id="name-error"></span>
                        </div>
                        @error('name')
                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-5 mb-3">
                        <div class="modern-field">
                            <label for="crew_id">Plantel</label>
                            <select class="modern-input text-uppercase" name="crew_id" id="crew_id">
                                @foreach($crews as $crew)
                                    <option value="{{ $crew->id }}" {{ (string) old('crew_id', $course->crew_id) === (string) $crew->id ? 'selected' : '' }}>
                                        {{ $crew->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('crew_id')
                            <div style="color: #991b1b; font-size: 14px; margin-top: 4px;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: center; gap: 12px; margin-top: 32px; margin-bottom: 60px;">
            <button id="saveCourse" type="submit" class="btn-modern btn-primary" style="min-width: 200px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 5H7C6.46957 5 5.96086 5.21071 5.58579 5.58579C5.21071 5.96086 5 6.46957 5 7V19C5 19.5304 5.21071 20.0391 5.58579 20.4142C5.96086 20.7893 6.46957 21 7 21H17C17.5304 21 18.0391 20.7893 18.4142 20.4142C18.7893 20.0391 19 19.5304 19 19V7C19 6.46957 18.7893 5.96086 18.4142 5.58579C18.0391 5.21071 17.5304 5 17 5H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 5C9 5.53043 9.21071 6.03914 9.58579 6.41421C9.96086 6.78929 10.4696 7 11 7H13C13.5304 7 14.0391 6.78929 14.4142 6.41421C14.7893 6.03914 15 5.53043 15 5C15 4.46957 14.7893 3.96086 14.4142 3.58579C14.0391 3.21071 13.5304 3 13 3H11C10.4696 3 9.96086 3.21071 9.58579 3.58579C9.21071 3.96086 9 4.46957 9 5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Modificar Curso
            </button>
            <a href="{{ route('admin.catalogues.courses.show') }}" class="btn-modern btn-secondary" style="min-width: 200px;" onclick="showLoader(true)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Volver
            </a>
        </div>
    </form>
@endsection
