@extends('layout.mainLayout')
@section('title','Nueva pregunta')
@section('content')
<div class="card shadow ccont pb-3">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>Nueva Pregunta</h1>
            </div>
        </div>
        <form action="{{ route('admin.catalogues.questions.insert') }}" method="post" id="questionForm">
            @csrf
            <div class="row d-flex mt-5">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="subject_id"><b>Materia (Bachillerato):</b></label>
                        <select class="form-control text-uppercase" name="subject_id" id="subject_id" required>
                            <option value="">Seleccione una materia</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row d-flex mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="question_text"><b>Pregunta:</b></label>
                        <textarea class="form-control" id="question_text" name="question_text" rows="3" required></textarea>
                    </div>
                </div>
            </div>

            <div class="row d-flex mt-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="difficulty"><b>Dificultad:</b></label>
                        <select class="form-control" name="difficulty" id="difficulty" required>
                            <option value="facil">Fácil</option>
                            <option value="medio" selected>Medio</option>
                            <option value="dificil">Difícil</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="explanation"><b>Explicación (opcional):</b></label>
                        <textarea class="form-control" id="explanation" name="explanation" rows="2"></textarea>
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
                <div class="row d-flex align-items-center mt-2 option-row" data-option="0">
                    <div class="col-auto pr-2">
                        <input class="form-check-input correct-radio" type="radio" name="correct_option" value="0" required style="margin-top: 0.5rem;">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control option-input" name="options[0][text]" placeholder="Opción 1" required>
                    </div>
                    <div class="col-auto">
                        <span class="material-symbols-outlined bg-red remove-option" style="display:none; cursor:pointer;">
                            <a>delete</a>
                        </span>
                    </div>
                </div>

                <div class="row d-flex align-items-center mt-2 option-row" data-option="1">
                    <div class="col-auto pr-2">
                        <input class="form-check-input correct-radio" type="radio" name="correct_option" value="1" required style="margin-top: 0.5rem;">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control option-input" name="options[1][text]" placeholder="Opción 2" required>
                    </div>
                    <div class="col-auto">
                        <span class="material-symbols-outlined bg-red remove-option" style="display:none; cursor:pointer;">
                            <a>delete</a>
                        </span>
                    </div>
                </div>

                <div class="row d-flex align-items-center mt-2 option-row" data-option="2">
                    <div class="col-auto pr-2">
                        <input class="form-check-input correct-radio" type="radio" name="correct_option" value="2" required style="margin-top: 0.5rem;">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control option-input" name="options[2][text]" placeholder="Opción 3" required>
                    </div>
                    <div class="col-auto">
                        <span class="material-symbols-outlined bg-red remove-option" style="display:none; cursor:pointer;">
                            <a>delete</a>
                        </span>
                    </div>
                </div>
            </div>

            <div class="row d-flex mt-3">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-secondary d-inline-flex align-items-center" id="addOption">
                        <span class="material-symbols-outlined" style="font-size: 20px; margin-right: 5px;">add</span>
                        <span>Agregar opción</span>
                    </button>
                </div>
            </div>

            <div class="row d-flex text-center mt-5">
                <div class="col">
                    <button type="submit" class="btn bg-orange text-white w-25">Guardar Pregunta</button><br><br>
                    <a href="{{ route('admin.catalogues.questions.show') }}">
                        <button type="button" class="btn btn-outline-orange w-25">Volver</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    let optionCount = 3;
    const maxOptions = 6;
    const minOptions = 3;

    // Agregar nueva opción
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

    // Eliminar opción
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

@endsection
