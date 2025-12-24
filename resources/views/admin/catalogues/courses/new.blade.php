@extends('layout.mainLayout')
@section('title','Nuevo curso')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">{{ isset($course) ? 'Modificar curso' : 'Nuevo curso' }}</h1>
        <p class="dashboard-subtitle">Define el nombre del curso y el plantel asociado.</p>
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

    <form action="{{ isset($course) ? route('admin.catalogues.courses.update', ['id' => $course->id]) : route('admin.catalogues.courses.insert') }}" method="post" onsubmit="showLoader(true)">
        @csrf
        @if(isset($course))
            @method('PUT')
        @endif

        <div class="modern-card">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 7L12 3L21 7L12 11L3 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3 7V17L12 21L21 17V7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 11V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>Informaci&oacute;n del curso</h2>
                </div>
            </div>

            <div style="padding: 24px;">
                <div class="row">
                    <div class="col-md-7 mb-3">
                        <div class="modern-field">
                            <label for="name">Nombre del curso</label>
                            <input type="text" class="form-control modern-input text-uppercase" id="name" placeholder="Nombre del curso" name="name" value="{{ old('name', isset($course) ? $course->name : '') }}">
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
                            <select class="form-select modern-input text-uppercase" name="crew_id" id="crew_id">
                                @foreach($crews as $crew)
                                    <option value="{{ $crew->id }}" {{ (string) old('crew_id', isset($course) ? $course->crew_id : '') === (string) $crew->id ? 'selected' : '' }}>
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

        @if(isset($course))
            <input type="hidden" name="editCourseId" value="{{ $course->id }}">
        @endif

        <div style="display: flex; justify-content: center; gap: 12px; margin-top: 32px; margin-bottom: 60px;">
            <button id="saveCourse" type="submit" class="btn-modern btn-primary" style="min-width: 200px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21 12L9 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M21 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M21 12L15 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M9 6H6C4.34315 6 3 7.34315 3 9V15C3 16.6569 4.34315 18 6 18H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ isset($course) ? 'Modificar curso' : 'Guardar curso' }}
            </button>
            <a href="{{ route('admin.catalogues.courses.show') }}" class="btn-modern" style="min-width: 200px; background: white; color: #6b7280; border: 1px solid #e5e7eb;" onclick="showLoader(true)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Volver
            </a>
        </div>
    </form>
@endsection
