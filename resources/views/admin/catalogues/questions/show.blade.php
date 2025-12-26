@extends('layout.mainLayout')
@section('title', 'Banco de Preguntas')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Banco de Preguntas</h1>
        <p class="dashboard-subtitle">Gestiona las preguntas para exámenes de bachillerato</p>
    </div>

    <div class="modern-card">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8.228 9C8.65027 7.64509 9.48473 6.46523 10.5998 5.63985C11.7149 4.81448 13.0509 4.38794 14.4142 4.42231C15.7775 4.45667 17.0901 4.94997 18.1611 5.82609C19.2321 6.70222 20.0048 7.91217 20.36 9.27797C20.7153 10.6438 20.6338 12.0899 20.1281 13.403C19.6225 14.7161 18.7201 15.8271 17.5563 16.583C16.3926 17.3388 15.0269 17.7001 13.6512 17.6148C12.2754 17.5294 10.9622 16.9018 9.913 15.823L9 21L3 15L8.228 13.973C7.71972 12.5065 7.74669 10.9032 8.30445 9.4548" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 10H16V14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Preguntas Registradas</h2>
            </div>
            @if(Auth::user()->role_id == 7)
                <a href="{{ route('admin.catalogues.questions.new') }}" class="btn-modern btn-primary" onclick="showLoader(true)">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Nueva Pregunta
                </a>
            @endif
        </div>

        <div style="padding: 24px; background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
            <div class="row">
                <div class="col-md-5">
    <div class="modern-field">
        <label for="subjectFilter">Filtrar por Materia</label>
        <select id="subjectFilter" class="form-select modern-input">
            <option value="">Todas las materias</option>
            @foreach ($subjects as $subject)
                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="col-md-4">
    <div class="modern-field">
        <label for="difficultyFilter">Filtrar por Dificultad</label>
        <select id="difficultyFilter" class="form-select modern-input">
            <option value="">Todas las dificultades</option>
            <option value="facil">F&aacute;cil</option>
            <option value="medio">Medio</option>
            <option value="dificil">Dif&iacute;cil</option>
        </select>
    </div>
</div>
<div class="col-md-3 d-flex align-items-end">
    <p style="margin: 0; height: 48px; padding: 0 16px; display: flex; align-items: center; justify-content: center; background: white; border-radius: 12px; font-size: 14px; color: #6b7280; width: 100%; text-align: center;">
        <strong id="questionCount" style="color: #F57F17; font-size: 18px;">{{ $questions->count() }}</strong> pregunta(s)
    </p>
</div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="modern-table" id="questionsTable">
                <thead>
                    <tr>
                        <th style="width: 35%;">Pregunta</th>
                        <th style="width: 20%;">Materia</th>
                        <th style="width: 15%;">Creado por</th>
                        <th style="width: 10%;" class="text-center">Dificultad</th>
                        <th style="width: 8%;" class="text-center">Opciones</th>
                        <th style="width: 12%;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questions as $question)
                        <tr data-subject="{{ $question->subject_id }}" data-difficulty="{{ $question->difficulty }}">
                            <td>{{ Str::limit($question->question_text, 100) }}</td>
                            <td class="text-uppercase">{{ $question->subject->name }}</td>
                            <td>{{ $question->creator->name }} {{ $question->creator->surnames }}</td>
                            <td class="text-center">
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
                                        default:
                                            $difficultyClass = 'badge-secondary';
                                            $difficultyText = $question->difficulty;
                                    }
                                @endphp
                                <span class="badge {{ $difficultyClass }}">{{ $difficultyText }}</span>
                            </td>
                            <td class="text-center font-medium">{{ $question->options->count() }}</td>
                            <td>
                                <div class="action-buttons">
                                    @if(Auth::user()->role_id == 7 && $question->created_by == Auth::id())
                                        <a href="{{ route('admin.catalogues.questions.edit', ['id' => $question->id]) }}"
                                           class="action-btn action-edit"
                                           onclick="showLoader(true)"
                                           title="Editar pregunta">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M18.5 2.50001C18.8978 2.10219 19.4374 1.87869 20 1.87869C20.5626 1.87869 21.1022 2.10219 21.5 2.50001C21.8978 2.89784 22.1213 3.4374 22.1213 4.00001C22.1213 4.56262 21.8978 5.10219 21.5 5.50001L12 15L8 16L9 12L18.5 2.50001Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </a>
                                        <form method="POST"
                                              action="{{ route('admin.catalogues.questions.delete', ['id' => $question->id]) }}"
                                              id="delete-question-{{ $question->id }}"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="action-btn action-delete"
                                                    onclick="confirmDelete('question',{{ $question->id }})"
                                                    title="Eliminar pregunta">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 6H5H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M8 6V4C8 3.46957 8.21071 2.96086 8.58579 2.58579C8.96086 2.21071 9.46957 2 10 2H14C14.5304 2 15.0391 2.21071 15.4142 2.58579C15.7893 2.96086 16 3.46957 16 4V6M19 6V20C19 20.5304 18.7893 21.0391 18.4142 21.4142C18.0391 21.7893 17.5304 22 17 22H7C6.46957 22 5.96086 21.7893 5.58579 21.4142C5.21071 21.0391 5 20.5304 5 20V6H19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.catalogues.questions.edit', ['id' => $question->id]) }}"
                                           class="action-btn"
                                           onclick="showLoader(true)"
                                           title="Ver pregunta"
                                           style="color: #6b7280;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 12C1 12 5 4 12 4C19 4 23 12 23 12C23 12 19 20 12 20C5 20 1 12 1 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const subjectFilter = document.getElementById('subjectFilter');
        const difficultyFilter = document.getElementById('difficultyFilter');
        const tableRows = document.querySelectorAll('#questionsTable tbody tr');
        const questionCount = document.getElementById('questionCount');

        function filterQuestions() {
            const selectedSubject = subjectFilter.value;
            const selectedDifficulty = difficultyFilter.value;
            let visibleCount = 0;

            tableRows.forEach(row => {
                const rowSubject = row.getAttribute('data-subject');
                const rowDifficulty = row.getAttribute('data-difficulty');

                const subjectMatch = !selectedSubject || rowSubject === selectedSubject;
                const difficultyMatch = !selectedDifficulty || rowDifficulty === selectedDifficulty;

                if (subjectMatch && difficultyMatch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            questionCount.textContent = visibleCount;
        }

        subjectFilter.addEventListener('change', filterQuestions);
        difficultyFilter.addEventListener('change', filterQuestions);

        function confirmDelete(type, id) {
            if (confirm('¿Está seguro de que desea eliminar esta pregunta?')) {
                document.getElementById('delete-' + type + '-' + id).submit();
            }
        }
    </script>
@endsection

