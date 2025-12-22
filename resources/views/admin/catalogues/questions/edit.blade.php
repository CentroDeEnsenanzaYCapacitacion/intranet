@extends('layout.mainLayout')
@section('title', Auth::user()->role_id == 7 && $question->created_by == Auth::id() ? 'Editar pregunta' : 'Ver pregunta')
@section('content')
<div class="card shadow ccont pb-3">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>{{ Auth::user()->role_id == 7 && $question->created_by == Auth::id() ? 'Editar Pregunta' : 'Ver Pregunta' }}</h1>
            </div>
        </div>

        @if(Auth::user()->role_id == 7 && $question->created_by == Auth::id())
        {{-- Modo edición: solo para el profesor creador --}}
        <form action="{{ route('admin.catalogues.questions.update', ['id' => $question->id]) }}" method="post" id="questionForm">
            @csrf
            @method('PUT')
            <div class="row d-flex mt-5">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="subject_id"><b>Materia (Bachillerato):</b></label>
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

            <div class="row d-flex mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="question_text"><b>Pregunta:</b></label>
                        <textarea class="form-control" id="question_text" name="question_text" rows="3" required>{{ $question->question_text }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row d-flex mt-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="difficulty"><b>Dificultad:</b></label>
                        <select class="form-control" name="difficulty" id="difficulty" required>
                            <option value="facil" {{ $question->difficulty == 'facil' ? 'selected' : '' }}>Fácil</option>
                            <option value="medio" {{ $question->difficulty == 'medio' ? 'selected' : '' }}>Medio</option>
                            <option value="dificil" {{ $question->difficulty == 'dificil' ? 'selected' : '' }}>Difícil</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="explanation"><b>Explicación (opcional):</b></label>
                        <textarea class="form-control" id="explanation" name="explanation" rows="2">{{ $question->explanation }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row d-flex mt-4">
                <div class="col-md-12">
                    <h4>Opciones de respuesta</h4>
                    <p class="text-muted">Agregue entre 3 y 6 opciones. Marque la opción correcta.</p>
                </div>
            </div>

            <div id="optionsContainer">
                @foreach($question->options as $index => $option)
                <div class="row d-flex align-items-center mt-2 option-row" data-option="{{ $index }}">
                    <div class="col-auto pr-2">
                        <input class="form-check-input correct-radio" type="radio" name="correct_option"
                               value="{{ $index }}" {{ $option->is_correct ? 'checked' : '' }} required style="margin-top: 0.5rem;">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control option-input"
                               name="options[{{ $index }}][text]"
                               placeholder="Opción {{ $index + 1 }}"
                               value="{{ $option->option_text }}" required>
                    </div>
                    <div class="col-auto">
                        <span class="material-symbols-outlined bg-red remove-option"
                              style="display:{{ count($question->options) > 3 && $index >= 3 ? 'inline-block' : 'none' }}; cursor:pointer;">
                            <a>delete</a>
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="row d-flex mt-3">
                <div class="col-md-12">
                    <button type="button" class="btn btn-secondary d-inline-flex align-items-center" id="addOption">
                        <span class="material-symbols-outlined" style="font-size: 20px; margin-right: 5px;">add</span>
                        <span>Agregar opción</span>
                    </button>
                </div>
            </div>

            <div class="row d-flex text-center mt-5">
                <div class="col">
                    <button type="submit" class="btn bg-orange text-white w-25">Actualizar Pregunta</button><br><br>
                    <a href="{{ route('admin.catalogues.questions.show') }}">
                        <button type="button" class="btn btn-outline-orange w-25">Volver</button>
                    </a>
                </div>
            </div>
        </form>

        <script>
            let optionCount = {{ count($question->options) }};
            const maxOptions = 6;
            const minOptions = 3;

            document.getElementById('addOption').addEventListener('click', function() {
                if (optionCount < maxOptions) {
                    const container = document.getElementById('optionsContainer');
                    const newOption = document.createElement('div');
                    newOption.className = 'row d-flex align-items-center mt-2 option-row';
                    newOption.setAttribute('data-option', optionCount);
                    newOption.innerHTML = `
                        <div class="col-auto pr-2">
                            <input class="form-check-input correct-radio" type="radio" name="correct_option" value="${optionCount}" required style="margin-top: 0.5rem;">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control option-input" name="options[${optionCount}][text]" placeholder="Opción ${optionCount + 1}" required>
                        </div>
                        <div class="col-auto">
                            <span class="material-symbols-outlined bg-red remove-option" style="cursor:pointer;">
                                <a>delete</a>
                            </span>
                        </div>
                    `;
                    container.appendChild(newOption);
                    optionCount++;
                    updateRemoveButtons();
                }

                if (optionCount >= maxOptions) {
                    this.disabled = true;
                }
            });

            document.getElementById('optionsContainer').addEventListener('click', function(e) {
                if (e.target.closest('.remove-option')) {
                    const optionRow = e.target.closest('.option-row');
                    optionRow.remove();
                    optionCount--;
                    document.getElementById('addOption').disabled = false;
                    updateRemoveButtons();
                    reindexOptions();
                }
            });

            function updateRemoveButtons() {
                const options = document.querySelectorAll('.option-row');
                const removeButtons = document.querySelectorAll('.remove-option');

                removeButtons.forEach((btn, index) => {
                    // Solo mostrar papeleras cuando hay más de 3 opciones, y solo en las opciones 4+
                    if (options.length > minOptions && index >= minOptions) {
                        btn.style.display = 'inline-block';
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
        </script>

        @else
        {{-- Modo visualización: para admin, director y otros profesores --}}
        <div class="row d-flex mt-5">
            <div class="col-md-12">
                <div class="form-group">
                    <label><b>Materia (Bachillerato):</b></label>
                    <p class="form-control-plaintext text-uppercase">{{ $question->subject->name }}</p>
                </div>
            </div>
        </div>

        <div class="row d-flex mt-3">
            <div class="col-md-12">
                <div class="form-group">
                    <label><b>Pregunta:</b></label>
                    <p class="form-control-plaintext">{{ $question->question_text }}</p>
                </div>
            </div>
        </div>

        <div class="row d-flex mt-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>Dificultad:</b></label>
                    <p class="form-control-plaintext">
                        @php
                            $difficultyText = '';
                            $backgroundColor = '';
                            switch($question->difficulty) {
                                case 'facil':
                                    $difficultyText = 'Fácil';
                                    $backgroundColor = '#28a745';
                                    break;
                                case 'medio':
                                    $difficultyText = 'Medio';
                                    $backgroundColor = '#ffc107';
                                    break;
                                case 'dificil':
                                    $difficultyText = 'Difícil';
                                    $backgroundColor = '#dc3545';
                                    break;
                            }
                        @endphp
                        <span style="background-color: {{ $backgroundColor }}; color: white; font-size: 13px; padding: 6px 12px; border-radius: 4px; display: inline-block; font-weight: 500; min-width: 70px; text-align: center;">{{ $difficultyText }}</span>
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label><b>Creado por:</b></label>
                    <p class="form-control-plaintext">{{ $question->creator->name }} {{ $question->creator->surnames }}</p>
                </div>
            </div>
        </div>

        @if($question->explanation)
        <div class="row d-flex mt-3">
            <div class="col-md-12">
                <div class="form-group">
                    <label><b>Explicación:</b></label>
                    <p class="form-control-plaintext">{{ $question->explanation }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="row d-flex mt-4">
            <div class="col-md-12">
                <h4>Opciones de respuesta</h4>
            </div>
        </div>

        <div class="mt-3">
            @foreach($question->options as $index => $option)
            <div class="row d-flex align-items-center mt-2">
                <div class="col-auto pr-2">
                    <input class="form-check-input" type="radio" {{ $option->is_correct ? 'checked' : '' }} disabled style="margin-top: 0.5rem;">
                </div>
                <div class="col">
                    <p class="form-control-plaintext mb-0">
                        {{ $option->option_text }}
                        @if($option->is_correct)
                            <span style="background-color: #28a745; color: white; font-size: 11px; padding: 3px 8px; border-radius: 3px; display: inline-block; font-weight: 500; margin-left: 10px;">Correcta</span>
                        @endif
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row d-flex text-center mt-5">
            <div class="col">
                <a href="{{ route('admin.catalogues.questions.show') }}">
                    <button type="button" class="btn btn-outline-orange w-25">Volver</button>
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
