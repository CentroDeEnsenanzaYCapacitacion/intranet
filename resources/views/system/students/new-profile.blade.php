@extends('layout.mainLayout')
@section('title', 'Informes')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/calendar-modal.css') }}">
@endpush
@section('content')

    @php
        $yearNow = date('Y');
        $years = [];
        for ($i = $yearNow - 5; $i <= $yearNow + 2; $i++) {
            $years[] = substr($i, -2);
        }
    @endphp

    @if ($errors->any())
        <div class="alert alert-danger mt-content">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow ccont pb-3">
        <div class="card-body">
            <div class="row d-flex text-center mt-3">
                <div class="col">
                    <h1>Ficha de estudiante</h1>
                </div>
            </div>
            <div class="row d-flex text-center mt-5">
                <div class="col-3">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="card shadow custom-size">
                            <img src="{{ route('system.student.image', ['student_id' => $student->id]) }}"
                                alt="Profile Image">
                        </div>
                    </div>
                    <button type="button" class="btn bg-orange text-white" onclick="saveFormDataAndRedirect()">Cambiar
                        Fotografía</button>
                </div>
                <div class="col">
                    <div class="text-start text-uppercase">
                        <form method="POST" action="{{ route('system.student.update') }}">
                            @csrf
                            <input type="hidden" name="operation" value="new" />
                            <input type="hidden" name="crew_id" value="{{ $student->crew_id }}" />
                            <input type="hidden" name="student_id" value="{{ $student->id }}" />
                            <input type="hidden" name="name" value="{{ $student->name }}" />
                            <input type="hidden" name="surnames" value="{{ $student->surnames }}" />
                            <input type="hidden" name="course_id" value="{{ $student->course_id }}" />
                            <input type="hidden" name="genre" value="{{ $student->genre }}" />
                            <h5 class="text-orange"><b>Información general
                                    <hr>
                                </b></h5>
                            <b>Nombre: </b>{{ $student->surnames . ', ' . $student->name }}<br>
                            <b>Fecha de nacimiento:</b>
                            <input placeholder="selecciona..." type="text" id="datePicker" name="birthdate"
                                style="height: 35px !important; width:120px;" class="form-control"
                                value="{{ old('birthdate', $savedData['birthdate'] ?? '') }}">
                            <br>
                            <b>CURP: </b><input class="form-control text-uppercase" name="curp" type="text"
                                value="{{ old('curp', $savedData['curp'] ?? '') }}" /><br>
                            <b>Dirección: </b><input class="form-control text-uppercase" type="text" name="address"
                                value="{{ old('address', $savedData['address'] ?? '') }}" /> <br>
                            <b>Colonia: </b><input class="form-control text-uppercase" type="text" name="colony"
                                value="{{ old('colony', $savedData['colony'] ?? '') }}" /><br>
                            <b>Municipio: </b><input class="form-control text-uppercase" type="text" name="municipality"
                                value="{{ old('municipality', $savedData['municipality'] ?? '') }}" /><br>
                            <b>C.P.: </b><input class="form-control text-uppercase" type="text" name="pc"
                                value="{{ old('pc', $savedData['pc'] ?? '') }}" /><br>
                            <b>Género: </b>
                            @if ($student->genre == 'M')
                                Mujer
                            @elseif($student->genre == 'H')
                                Hombre
                            @else
                                No Binario
                            @endif
                            <br>
                            <b>Teléfono: </b><input class="form-control text-uppercase" type="text" name="phone"
                                value="{{ old('phone', $savedData['phone'] ?? ($student->phone ?? '')) }}" /><br>
                            <b>Celular: </b><input class="form-control text-uppercase" type="text" name="cel_phone"
                                value="{{ old('cel_phone', $savedData['cel_phone'] ?? ($student->cel_phone ?? '')) }}" /><br>
                            <b>Correo electrónico: </b><input class="form-control text-uppercase" type="text"
                                name="email"
                                value="{{ old('email', $savedData['email'] ?? $student->email) }}" /><br><br>
                            <h5 class="text-orange"><b>Información académica
                                    <hr>
                                </b></h5>
                            <b>Matrícula: </b>{{ $student->crew->name[0] . '/' . $student->id }}<br>
                            <b>Curso: </b>{{ $student->course->name }}<br>
                            <b>Generación: </b>
                            <div class="d-flex">
                                <select name="gen_month" class="form-control w-25">
                                    <option value="F" {{ old('gen_month') == 'F' ? 'selected' : '' }}>Febrero</option>
                                    <option value="M" {{ old('gen_month') == 'M' ? 'selected' : '' }}>Mayo</option>
                                    <option value="A" {{ old('gen_month') == 'A' ? 'selected' : '' }}>Agosto</option>
                                    <option value="N" {{ old('gen_month') == 'N' ? 'selected' : '' }}>Noviembre
                                    </option>
                                </select>
                                <select name="gen_year" class="form-control w-25">
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}"
                                            {{ old('gen_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div><br>
                            <div class="form-group">
                                <label for="exampleSelect"><b>Periodicidad de pago:</b></label>
                                <select class="form-control text-uppercase" name="payment_periodicity_id"
                                    id="payment_periodicity_id">
                                    @foreach ($payment_periodicities as $payment_periodicity)
                                        <option value="{{ $payment_periodicity->id }}"
                                            {{ old('payment_periodicity_id') == $payment_periodicity->id ? 'selected' : '' }}>
                                            {{ $payment_periodicity->name }}</option>
                                    @endforeach
                                </select>
                            </div><br>
                            <div class="form-group">
                                <label for="exampleSelect"><b>Horario:</b></label>
                                <select class="form-control text-uppercase" name="schedule_id" id="schedule_id">
                                    @foreach ($schedules as $schedule)
                                        <option value="{{ $schedule->id }}"
                                            {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                            {{ $schedule->name }}</option>
                                    @endforeach
                                </select>
                            </div><br>
                            <div>
                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" value="false" class="btn-check text-uppercase"
                                        name="sabbatine" id="sabf" autocomplete="off"
                                        {{ old('sabbatine', 'false') == 'false' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-orange text-uppercase"
                                        for="sabf">Intersemanal</label>

                                    <input type="radio" value="true" class="btn-check text-uppercase"
                                        name="sabbatine" id="sabt" autocomplete="off"
                                        {{ old('sabbatine') == 'true' ? 'checked' : '' }}>
                                    <label class="btn btn-outline-orange text-uppercase" for="sabt">Sabatino</label>
                                </div>
                            </div><br>
                            <b>Colegiatura: </b><input class="form-control" type="number" step="0.01" min="0.01"
                                name="tuition" value="{{ old('tuition', $savedData['tuition'] ?? '') }}"
                                placeholder="0.00" required /><br>
                            @if ($errors->has('tuition'))
                                <div class="text-danger">{{ $errors->first('tuition') }}</div>
                            @endif
                            <div class="form-group">
                                <label for="exampleSelect"><b>Modalidad:</b></label>
                                <select class="form-control text-uppercase" name="modality_id" id="modality_id">
                                    @foreach ($modalities as $modality)
                                        <option value="{{ $modality->id }}"
                                            {{ old('modality_id') == $modality->id ? 'selected' : '' }}>
                                            {{ $modality->name }}</option>
                                    @endforeach
                                </select>
                            </div><br>
                            <b>Inicio: </b>
                            <input placeholder="selecciona..." type="text" id="datePicker" name="start"
                                style="height: 35px !important; width:120px;" class="form-control"
                                value="{{ old('start', $savedData['start'] ?? '') }}"><br><br>
                            <h5 class="text-orange"><b>Información de tutor
                                    <hr>
                                </b></h5>
                            <b>Nombre: </b><input class="form-control text-uppercase" type="text" name="tutor_name"
                                value="{{ old('tutor_name', $savedData['tutor_name'] ?? '') }}" /><br>
                            <b>Apellidos: </b><input class="form-control text-uppercase" type="text"
                                name="tutor_surnames"
                                value="{{ old('tutor_surnames', $savedData['tutor_surnames'] ?? '') }}" /><br>
                            <b>Teléfono: </b><input class="form-control text-uppercase" type="text" name="tutor_phone"
                                value="{{ old('tutor_phone', $savedData['tutor_phone'] ?? '') }}" /><br>
                            <b>Celular: </b><input class="form-control text-uppercase" type="text"
                                name="tutor_cel_phone"
                                value="{{ old('tutor_cel_phone', $savedData['tutor_cel_phone'] ?? '') }}" /><br>
                            <b>Parentesco: </b><input class="form-control text-uppercase" type="text"
                                name="relationship"
                                value="{{ old('relationship', $savedData['relationship'] ?? '') }}" /><br>
                            <h5 class="text-orange"><b>Documentación
                                    <hr>
                                </b></h5>
                            <table class="table table-sm">
                                @foreach ($student->documents as $document)
                                    <tr>
                                        <td class="align-middle">{{ $document->name }}</td>
                                        <td class="align-middle">
                                            @if ($document->pivot->uploaded)
                                                <span class="badge bg-success">&nbsp;</span>
                                            @else
                                                <span class="badge bg-danger">&nbsp;</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            @if ($document->pivot->uploaded)
                                                @php
                                                    $directory = 'profiles/' . $student->id;
                                                    $baseFileName = str_replace(' ', '_', strtolower($document->name));
                                                    $extensions = ['jpeg', 'png', 'jpg', 'gif', 'svg', 'webp', 'pdf'];
                                                    $isPdf = false;
                                                    foreach ($extensions as $ext) {
                                                        $possiblePath = $directory . '/' . $baseFileName . '.' . $ext;
                                                        if (Storage::disk('local')->exists($possiblePath)) {
                                                            if ($ext === 'pdf') {
                                                                $isPdf = true;
                                                            }
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                <a href="{{ route('system.student.document', ['student_id' => $student->id, 'document_id' => $document->id]) }}"
                                                    target="_blank">
                                                    @if ($isPdf)
                                                        <i class="fas fa-file-pdf"
                                                            style="font-size: 40px; color: #dc3545;"></i>
                                                    @else
                                                        <img src="{{ route('system.student.document', ['student_id' => $student->id, 'document_id' => $document->id]) }}"
                                                            alt="{{ $document->name }}"
                                                            style="max-width: 60px; max-height: 60px; border: 1px solid #ddd; border-radius: 4px; display: block; margin: 0 auto;">
                                                    @endif
                                                </a>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @if (!$document->pivot->uploaded)
                                                <button type="button" class="btn btn-outline-orange btn-sm"
                                                    onclick="openDocumentModal({{ $document->id }}, '{{ $document->name }}')">Añadir
                                                    documento</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table><br>
                            <h5 class="text-orange"><b>Observaciones
                                    <hr>
                                </b></h5>
                            @foreach ($student->observations as $observation)
                                <div style="display: flex; margin-bottom: 10px; align-items: flex-start;">
                                    <div style="width: 90px; font-weight: bold;">
                                        {{ $observation->created_at->format('d/m/Y') }}</div>
                                    <div style="flex-grow: 1; margin-left: 40px; margin-right: 40px; text-align: justify;">
                                        {{ $observation->description }}</div>
                                </div>
                            @endforeach
                            <div class="mt-3">
                                <textarea class="form-control text-uppercase" name="observation" id="observation" rows="3"
                                    placeholder="Escribe una nueva observación..."></textarea>
                            </div>
                            <div class="d-flex justify-content-center mt-3"><button class="btn bg-orange text-white"
                                    type="submit"
                                    onclick="if(this.form.checkValidity()) showLoader(true)">Guardar</button></div><br><br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="custom-modal" id="documentModal" style="display:none;" onclick="closeOnOverlay(event, 'documentModal')">
        <div class="custom-modal-content">
            <span class="custom-modal-close" onclick="closeModal('documentModal')">&times;</span>
            <h5 id="documentModalTitle">Añadir documento</h5>
            <form method="POST" action="{{ route('system.student.upload-document') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <input type="hidden" name="document_id" id="document_id">

                <div class="mb-3">
                    <label for="document_file" class="form-label"><b>Selecciona el archivo:</b></label>
                    <input type="file" class="form-control" id="document_file" name="document_file"
                        accept="image/*,.pdf" required>
                    <small class="form-text text-muted">
                        Formatos permitidos: Imágenes (JPG, PNG, etc.) y PDF
                    </small>
                </div>

                <div class="mt-4 text-end">
                    <button type="button" class="btn btn-secondary me-2"
                        onclick="closeModal('documentModal')">Cancelar</button>
                    <button type="submit" class="btn bg-orange text-white">Subir documento</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function saveFormDataAndRedirect() {
                const formData = {
                    birthdate: document.querySelector('input[name="birthdate"]').value,
                    curp: document.querySelector('input[name="curp"]').value,
                    address: document.querySelector('input[name="address"]').value,
                    colony: document.querySelector('input[name="colony"]').value,
                    municipality: document.querySelector('input[name="municipality"]').value,
                    pc: document.querySelector('input[name="pc"]').value,
                    phone: document.querySelector('input[name="phone"]').value,
                    cel_phone: document.querySelector('input[name="cel_phone"]').value,
                    email: document.querySelector('input[name="email"]').value,
                    gen_month: document.querySelector('select[name="gen_month"]').value,
                    gen_year: document.querySelector('select[name="gen_year"]').value,
                    payment_periodicity_id: document.querySelector('select[name="payment_periodicity_id"]').value,
                    schedule_id: document.querySelector('select[name="schedule_id"]').value,
                    sabbatine: document.querySelector('input[name="sabbatine"]:checked').value,
                    tuition: document.querySelector('input[name="tuition"]').value,
                    modality_id: document.querySelector('select[name="modality_id"]').value,
                    start: document.querySelector('input[name="start"]').value,
                    tutor_name: document.querySelector('input[name="tutor_name"]').value,
                    tutor_surnames: document.querySelector('input[name="tutor_surnames"]').value,
                    tutor_phone: document.querySelector('input[name="tutor_phone"]').value,
                    tutor_cel_phone: document.querySelector('input[name="tutor_cel_phone"]').value,
                    relationship: document.querySelector('input[name="relationship"]').value
                };

                fetch('{{ route('system.student.save-form-data', ['student_id' => $student->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                }).then(() => {
                    window.location.href =
                        '{{ route('system.student.profile-image', ['student_id' => $student->id]) }}';
                });
            }

            function openModal(modalId) {
                const modal = document.getElementById(modalId);
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);
            }

            function closeModal(modalId) {
                const modal = document.getElementById(modalId);
                modal.classList.remove('show');
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            }

            function closeOnOverlay(event, modalId) {
                if (event.target.id === modalId) {
                    closeModal(modalId);
                }
            }

            function openDocumentModal(documentId, documentName) {
                document.getElementById('document_id').value = documentId;
                document.getElementById('documentModalTitle').textContent = 'Añadir documento: ' + documentName;
                openModal('documentModal');
            }
        </script>
    @endpush
@endsection
