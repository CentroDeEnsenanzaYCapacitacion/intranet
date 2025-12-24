@extends('layout.mainLayout')
@section('title', Auth::user()->role_id == 7 && $question->created_by == Auth::id() ? 'Editar pregunta' : 'Ver pregunta')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">{{ Auth::user()->role_id == 7 && $question->created_by == Auth::id() ? 'Editar Pregunta' : 'Ver Pregunta' }}</h1>
        <p class="dashboard-subtitle">{{ Auth::user()->role_id == 7 && $question->created_by == Auth::id() ? 'Modifica los datos de la pregunta' : 'Información detallada de la pregunta' }}</p>
    </div>

    @if(Auth::user()->role_id == 7 && $question->created_by == Auth::id())
    <form action="{{ route('admin.catalogues.questions.update', ['id' => $question->id]) }}" method="post" id="questionForm">
        @csrf
        @method('PUT')
        <div class="modern-card">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>Datos de la Pregunta</h2>
                </div>
            </div>

            <div style="padding: 24px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="subject_id" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">Materia (Bachillerato):</label>
                            <select class="form-control text-uppercase" name="subject_id" id="subject_id" required>
                                <option value="">Seleccione una materia</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ $question->subject_id == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 16px;">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="question_text" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">Pregunta:</label>
                            <textarea class="form-control" id="question_text" name="question_text" rows="3" required>{{ $question->question_text }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 16px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="difficulty" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">Dificultad:</label>
                            <select class="form-control" name="difficulty" id="difficulty" required>
                                <option value="facil" {{ $question->difficulty == 'facil' ? 'selected' : '' }}>Fácil</option>
                                <option value="medio" {{ $question->difficulty == 'medio' ? 'selected' : '' }}>Medio</option>
                                <option value="dificil" {{ $question->difficulty == 'dificil' ? 'selected' : '' }}>Difícil</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="explanation" style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #374151;">Explicación (opcional):</label>
                            <textarea class="form-control" id="explanation" name="explanation" rows="2">{{ $question->explanation }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modern-card" style="margin-top: 24px;">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12L11 14L15 10M21 12C21 13.1819 20.7672 14.3522 20.3149 15.4442C19.8626 16.5361 19.1997 17.5282 18.364 18.364C17.5282 19.1997 16.5361 19.8626 15.4442 20.3149C14.3522 20.7672 13.1819 21 12 21C10.8181 21 9.64778 20.7672 8.55585 20.3149C7.46392 19.8626 6.47177 19.1997 5.63604 18.364C4.80031 17.5282 4.13738 16.5361 3.68508 15.4442C3.23279 14.3522 3 13.1819 3 12C3 9.61305 3.94821 7.32387 5.63604 5.63604C7.32387 3.94821 9.61305 3 12 3C14.3869 3 16.6761 3.94821 18.364 5.63604C20.0518 7.32387 21 9.61305 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h2>Opciones de Respuesta</h2>
                </div>
            </div>

            <div style="padding: 24px;">
                <p style="margin-bottom: 20px; color: #6b7280; font-size: 14px;">Agregue entre 3 y 6 opciones. Marque la opción correcta.</p>

                <div id="optionsContainer">
                    @foreach($question->options as $index => $option)
                    <div class="row d-flex align-items-center option-row" data-option="{{ $index }}" style="margin-bottom: 12px;">
                        <div class="col-auto" style="padding-right: 8px;">
                            <input class="form-check-input correct-radio" type="radio" name="correct_option"
                                   value="{{ $index }}" {{ $option->is_correct ? 'checked' : '' }} required style="margin-top: 0.5rem; cursor: pointer;">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control option-input"
                                   name="options[{{ $index }}][text]"
                                   placeholder="Opción {{ $index + 1 }}"
                                   value="{{ $option->option_text }}" required>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="action-btn action-delete remove-option"
                                    style="display:{{ count($question->options) > 3 && $index >= 3 ? 'inline-flex' : 'none' }};"
                                    title="Eliminar opción">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div style="margin-top: 20px;">
                    <button type="button" class="btn-modern btn-primary" id="addOption">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Agregar Opción
                    </button>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: center; gap: 12px; margin-top: 32px;">
            <button type="submit" class="btn-modern btn-primary" style="min-width: 200px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16L21 8V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M17 21V13H7V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 3V8H15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Actualizar Pregunta
            </button>
            <a href="{{ route('admin.catalogues.questions.show') }}" class="btn-modern" style="min-width: 200px; background: white; color: #6b7280; border: 1px solid #e5e7eb;" onclick="showLoader(true)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 12H5M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Volver
            </a>
        </div>
    </form>

    <script>
        let optionCount = {{ count($question->options) }};
        const maxOptions = 6;
        const minOptions = 3;
        const addBtn = document.getElementById('addOption');
        const toggleAddButton = () => {
            if (!addBtn) {
                return;
            }
            addBtn.style.display = optionCount >= maxOptions ? 'none' : 'inline-flex';
        };

        addBtn.addEventListener('click', function() {
            if (optionCount < maxOptions) {
                const container = document.getElementById('optionsContainer');
                const newOption = document.createElement('div');
                newOption.className = 'row d-flex align-items-center option-row';
                newOption.setAttribute('data-option', optionCount);
                newOption.style.marginBottom = '12px';
                newOption.innerHTML = `
                    <div class="col-auto" style="padding-right: 8px;">
                        <input class="form-check-input correct-radio" type="radio" name="correct_option" value="${optionCount}" required style="margin-top: 0.5rem; cursor: pointer;">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control option-input" name="options[${optionCount}][text]" placeholder="Opción ${optionCount + 1}" required>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="action-btn action-delete remove-option" title="Eliminar opción">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                `;
                container.appendChild(newOption);
                optionCount++;
                updateRemoveButtons();
            }

            toggleAddButton();
        });

        document.getElementById('optionsContainer').addEventListener('click', function(e) {
            if (e.target.closest('.remove-option')) {
                const optionRow = e.target.closest('.option-row');
                optionRow.remove();
                optionCount--;
                updateRemoveButtons();
                reindexOptions();
                toggleAddButton();
            }
        });

        function updateRemoveButtons() {
            const options = document.querySelectorAll('.option-row');
            const removeButtons = document.querySelectorAll('.remove-option');

            removeButtons.forEach((btn, index) => {
                if (options.length > minOptions && index >= minOptions) {
                    btn.style.display = 'inline-flex';
                } else {
                    btn.style.display = 'none';
                }
            });
        }

        function reindexOptions() {
            const options = document.querySelectorAll('.option-row');
            options.forEach((option, index) => {
                option.setAttribute('data-option', index);
                const radio = option.querySelector('.correct-radio');
                const input = option.querySelector('.option-input');

                radio.value = index;
                input.name = `options[${index}][text]`;
                input.placeholder = `Opción ${index + 1}`;
            });
            optionCount = options.length;
        }

        updateRemoveButtons();
        toggleAddButton();
    </script>

    @else
    <div class="modern-card">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12H15M9 16H15M17 21H7C5.89543 21 5 20.1046 5 19V5C5 3.89543 5.89543 3 7 3H12.5858C12.851 3 13.1054 3.10536 13.2929 3.29289L18.7071 8.70711C18.8946 8.89464 19 9.149 19 9.41421V19C19 20.1046 18.1046 21 17 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Información de la Pregunta</h2>
            </div>
        </div>

        <div style="padding: 24px;">
            <div class="row">
                <div class="col-md-12">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #374151;">Materia (Bachillerato):</label>
                        <p class="text-uppercase" style="font-size: 14px; color: #1a1a1a; margin: 0;">{{ $question->subject->name }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #374151;">Pregunta:</label>
                        <p style="font-size: 14px; color: #1a1a1a; margin: 0;">{{ $question->question_text }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #374151;">Dificultad:</label>
                        @php
                            $difficultyClass = '';
                            $difficultyText = '';
                            switch($question->difficulty) {
                                case 'facil':
                                    $difficultyClass = 'badge-success';
                                    $difficultyText = 'Fácil';
                                    break;
                                case 'medio':
                                    $difficultyClass = 'badge-warning';
                                    $difficultyText = 'Medio';
                                    break;
                                case 'dificil':
                                    $difficultyClass = 'badge-danger';
                                    $difficultyText = 'Difícil';
                                    break;
                            }
                        @endphp
                        <span class="badge {{ $difficultyClass }}">{{ $difficultyText }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #374151;">Creado por:</label>
                        <p style="font-size: 14px; color: #1a1a1a; margin: 0;">{{ $question->creator->name }} {{ $question->creator->surnames }}</p>
                    </div>
                </div>
            </div>

            @if($question->explanation)
            <div class="row">
                <div class="col-md-12">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #374151;">Explicación:</label>
                        <p style="font-size: 14px; color: #1a1a1a; margin: 0;">{{ $question->explanation }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="modern-card" style="margin-top: 24px;">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12L11 14L15 10M21 12C21 13.1819 20.7672 14.3522 20.3149 15.4442C19.8626 16.5361 19.1997 17.5282 18.364 18.364C17.5282 19.1997 16.5361 19.8626 15.4442 20.3149C14.3522 20.7672 13.1819 21 12 21C10.8181 21 9.64778 20.7672 8.55585 20.3149C7.46392 19.8626 6.47177 19.1997 5.63604 18.364C4.80031 17.5282 4.13738 16.5361 3.68508 15.4442C3.23279 14.3522 3 13.1819 3 12C3 9.61305 3.94821 7.32387 5.63604 5.63604C7.32387 3.94821 9.61305 3 12 3C14.3869 3 16.6761 3.94821 18.364 5.63604C20.0518 7.32387 21 9.61305 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Opciones de Respuesta</h2>
            </div>
        </div>

        <div style="padding: 24px;">
            <div style="background: #f9fafb; border-radius: 12px; padding: 16px;">
                @foreach($question->options as $index => $option)
                <div style="display: flex; align-items: center; padding: 6px 0;">
                    @if($option->is_correct)
                        <span style="display: inline-block; font-size: 14px; color: #065f46; background: #d1fae5; padding: 6px 12px; border-radius: 999px;">{{ $option->option_text }}</span>
                    @else
                        <span style="display: inline-block; font-size: 14px; color: #1a1a1a; background: transparent; padding: 6px 12px; border-radius: 999px;">{{ $option->option_text }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: center; margin-top: 32px;">
        <a href="{{ route('admin.catalogues.questions.show') }}" class="btn-modern" style="min-width: 200px; background: white; color: #6b7280; border: 1px solid #e5e7eb;" onclick="showLoader(true)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 12H5M12 19L5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Volver
        </a>
    </div>
    @endif
@endsection
