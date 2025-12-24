@extends('layout.mainLayout')
@section('title','Nueva pregunta')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Nueva pregunta</h1>
        <p class="dashboard-subtitle">Define la materia, dificultad y opciones de respuesta.</p>
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

    <form action="{{ route('admin.catalogues.questions.insert') }}" method="post" id="questionForm" onsubmit="showLoader(true)">
        @csrf
        <div class="modern-card">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 19.5V5C4 3.89543 4.89543 3 6 3H18C19.1046 3 20 3.89543 20 5V19.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4 19.5C4 20.8807 5.11929 22 6.5 22H17.5C18.8807 22 20 20.8807 20 19.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 7H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8 11H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8 15H12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
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

        <div class="modern-card">
            <div class="card-header-modern">
                <div class="header-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 6H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M4 18H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <h2>Opciones de respuesta</h2>
                </div>
                <button type="button" class="btn-modern btn-primary" id="addOption">
                    <span class="material-symbols-outlined" style="font-size: 20px; margin-right: 6px;">add</span>
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
<script>
    let optionCount = 3;
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
            newOption.className = 'row d-flex align-items-center mt-2 option-row';
            newOption.setAttribute('data-option', optionCount);
            newOption.innerHTML = `
                <div class="col-auto pr-2">
                    <input class="form-check-input correct-radio" type="radio" name="correct_option" value="${optionCount}" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control modern-input option-input" name="options[${optionCount}][text]" placeholder="Opci&oacute;n ${optionCount + 1}" required>
                </div>
                <div class="col-auto">
                    <button type="button" class="action-btn action-delete remove-option" title="Eliminar opci&oacute;n">
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
            input.placeholder = `Opci&oacute;n ${index + 1}`;
        });
        optionCount = options.length;
    }

    updateRemoveButtons();
    toggleAddButton();
</script>
@endpush
