@extends('layout.mainLayout')
@section('title','Nueva pregunta')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Nueva pregunta</h1>
        <p class="dashboard-subtitle">Define la materia, dificultad y opciones de respuesta.</p>
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

    <form action="{{ route('admin.catalogues.questions.insert') }}" method="post" id="questionForm" data-password-confirm>
        @csrf
        <div class="modern-card" style="margin-bottom: 24px;">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.228 9C8.65027 7.64509 9.48473 6.46523 10.5998 5.63985C11.7149 4.81448 13.0509 4.38794 14.4142 4.42231C15.7775 4.45667 17.0901 4.94997 18.1611 5.82609C19.2321 6.70222 20.0048 7.91217 20.36 9.27797C20.7153 10.6438 20.6338 12.0899 20.1281 13.403C19.6225 14.7161 18.7201 15.8271 17.5563 16.583C16.3926 17.3388 15.0269 17.7001 13.6512 17.6148C12.2754 17.5294 10.9622 16.9018 9.913 15.823L9 21L3 15L8.228 13.973C7.71972 12.5065 7.74669 10.9032 8.30445 9.4548" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 10H16V14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>Datos de la pregunta</h2>
                </div>
            </div>

            <div style="padding: 24px;">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <div class="modern-field">
                            <label for="subject_id">Materia (Bachillerato)</label>
                            <select class="form-select modern-input text-uppercase" name="subject_id" id="subject_id" required>
                                <option value="">Seleccione una materia</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="modern-field">
                            <label for="difficulty">Dificultad</label>
                            <select class="form-select modern-input text-uppercase" name="difficulty" id="difficulty" required>
                                <option value="facil">F&aacute;cil</option>
                                <option value="medio" selected>Medio</option>
                                <option value="dificil">Dif&iacute;cil</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="modern-field">
                            <label for="question_text">Pregunta</label>
                            <textarea class="form-control modern-textarea" id="question_text" name="question_text" rows="3" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="modern-field">
                            <label for="explanation">Explicaci&oacute;n (opcional)</label>
                            <textarea class="form-control modern-textarea" id="explanation" name="explanation" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modern-card" style="margin-bottom: 24px;">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12L11 14L15 10M21 12C21 13.1819 20.7672 14.3522 20.3149 15.4442C19.8626 16.5361 19.1997 17.5282 18.364 18.364C17.5282 19.1997 16.5361 19.8626 15.4442 20.3149C14.3522 20.7672 13.1819 21 12 21C10.8181 21 9.64778 20.7672 8.55585 20.3149C7.46392 19.8626 6.47177 19.1997 5.63604 18.364C4.80031 17.5282 4.13738 16.5361 3.68508 15.4442C3.23279 14.3522 3 13.1819 3 12C3 9.61305 3.94821 7.32387 5.63604 5.63604C7.32387 3.94821 9.61305 3 12 3C14.3869 3 16.6761 3.94821 18.364 5.63604C20.0518 7.32387 21 9.61305 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>Opciones de respuesta</h2>
                </div>
                <button type="button" class="btn-modern btn-primary" id="addOption">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Agregar opci&oacute;n
                </button>
            </div>

            <div style="padding: 24px;">
                <p class="text-muted" style="margin-top: 0;">Agregue entre 3 y 6 opciones. Marque la opci&oacute;n correcta.</p>

                <div id="optionsContainer">
                    <div class="row d-flex align-items-center mt-2 option-row" data-option="0">
                        <div class="col-auto pr-2">
                            <input class="form-check-input correct-radio" type="radio" name="correct_option" value="0" required>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control modern-input option-input" name="options[0][text]" placeholder="Opci&oacute;n 1" required>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="action-btn action-delete remove-option" style="display: none;" title="Eliminar opci&oacute;n">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="row d-flex align-items-center mt-2 option-row" data-option="1">
                        <div class="col-auto pr-2">
                            <input class="form-check-input correct-radio" type="radio" name="correct_option" value="1" required>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control modern-input option-input" name="options[1][text]" placeholder="Opci&oacute;n 2" required>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="action-btn action-delete remove-option" style="display: none;" title="Eliminar opci&oacute;n">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="row d-flex align-items-center mt-2 option-row" data-option="2">
                        <div class="col-auto pr-2">
                            <input class="form-check-input correct-radio" type="radio" name="correct_option" value="2" required>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control modern-input option-input" name="options[2][text]" placeholder="Opci&oacute;n 3" required>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="action-btn action-delete remove-option" style="display: none;" title="Eliminar opci&oacute;n">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: center; gap: 12px; margin-top: 32px; margin-bottom: 60px;">
            <button type="submit" class="btn-modern btn-primary" style="min-width: 200px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16L21 8V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 21V13H7V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 3V8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Guardar pregunta
            </button>
            <a href="{{ route('admin.catalogues.questions.show') }}" class="btn-modern" style="min-width: 200px; background: white; color: #6b7280; border: 1px solid #e5e7eb;" onclick="showLoader(true)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Volver
            </a>
        </div>
    </form>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/questions_options.js') }}"></script>
@endpush

@include('includes.password-confirm-modal')
