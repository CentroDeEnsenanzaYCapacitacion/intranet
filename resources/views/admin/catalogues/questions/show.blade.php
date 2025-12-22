@extends('layout.mainLayout')
@section('title', 'Banco de preguntas')
@section('content')
    <div class="card shadow ccont">
        <div class="card-body">
            <div class="row d-flex text-center mt-3">
                <div class="col">
                    <h1>Banco de preguntas</h1>
                </div>
            </div>

            <div class="row d-flex mt-4">
                <div class="col-md-6">
                    <label for="subjectFilter">Filtrar por Materia (Bachillerato):</label>
                    <select id="subjectFilter" class="form-control">
                        <option value="">Todas las materias</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}">
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="difficultyFilter">Filtrar por Dificultad:</label>
                    <select id="difficultyFilter" class="form-control">
                        <option value="">Todas las dificultades</option>
                        <option value="facil">Fácil</option>
                        <option value="medio">Medio</option>
                        <option value="dificil">Difícil</option>
                    </select>
                </div>
            </div>

            <div class="row d-flex mt-3">
                <div class="col">
                    <p class="text-muted mb-0">
                        <strong id="questionCount">{{ $questions->count() }}</strong> pregunta(s)
                    </p>
                </div>
            </div>

            <div class="row d-flex mt-5">
                <div class="col">
                    <table class="table" id="questionsTable">
                        <thead class="thead-dark">
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
                                <tr data-subject="{{ $question->subject_id }}"
                                    data-difficulty="{{ $question->difficulty }}">
                                    <td class="text-left align-middle">{{ Str::limit($question->question_text, 100) }}</td>
                                    <td class="text-uppercase align-middle">{{ $question->subject->name }}</td>
                                    <td class="align-middle">{{ $question->creator->name }} {{ $question->creator->surnames }}</td>
                                    <td class="text-center align-middle">
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
                                                default:
                                                    $difficultyText = $question->difficulty;
                                                    $backgroundColor = '#6c757d';
                                            }
                                        @endphp
                                        <span style="background-color: {{ $backgroundColor }}; color: white; font-size: 13px; padding: 6px 12px; border-radius: 4px; display: inline-block; font-weight: 500; min-width: 70px; text-align: center;">{{ $difficultyText }}</span>
                                    </td>
                                    <td class="text-center align-middle">{{ $question->options->count() }}</td>
                                    <td class="text-center align-middle">
                                        <div class="d-inline-flex">
                                            @if(Auth::user()->role_id == 7 && $question->created_by == Auth::id())
                                                <span class="material-symbols-outlined bg-edit">
                                                    <a onclick="showLoader(true)"
                                                       href="{{ route('admin.catalogues.questions.edit', ['id' => $question->id]) }}">edit</a>
                                                </span>
                                                <form method="POST" class="d-inline"
                                                      action="{{ route('admin.catalogues.questions.delete', ['id' => $question->id]) }}"
                                                      id="delete-question-{{ $question->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <span class="material-symbols-outlined bg-red">
                                                        <a onclick="confirmDelete('question',{{ $question->id }})">delete</a>
                                                    </span>
                                                </form>
                                            @else
                                                <span class="material-symbols-outlined bg-view">
                                                    <a onclick="showLoader(true)"
                                                       href="{{ route('admin.catalogues.questions.edit', ['id' => $question->id]) }}">visibility</a>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if(Auth::user()->role_id == 7)
                    <div class="d-flex justify-content-center mt-5 mb-2">
                        <a href="{{ route('admin.catalogues.questions.new') }}" class="btn bg-orange text-white mt-5">
                            Nueva Pregunta
                        </a>
                    </div>
                    @endif
                </div>
            </div>
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
