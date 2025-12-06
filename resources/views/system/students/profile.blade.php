@extends('layout.mainLayout')
@section('title','Informes')
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/calendar-modal.css') }}">
@endpush
@section('content')

@php
use Carbon\Carbon;

$fechaNacimiento = Carbon::createFromFormat('d/m/Y', $student->birthdate);
$fechaActual = Carbon::now();
$edad = $fechaActual->diffInYears($fechaNacimiento);
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
@if(session('success'))
    <div id="success" class="alert alert-success mt-content">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger mt-content">
        {{ session('error') }}
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
                        <img src="{{ route('system.student.image', ['student_id' => $student->id]) }}" alt="Profile Image">
                    </div>
                </div>
                <a href="{{ route('system.student.profile-image', ['student_id' => $student->id]) }}">
                <button class="btn bg-orange text-white">Cambiar Fotografía</button></a>
            </div>
            <div class="col">
                <div class="text-start text-uppercase">
                    <form method="POST" action="{{ route('system.student.update') }}">
                        @csrf
                        <input type="hidden" name="operation" value="update"/>
                        <input type="hidden" name="student_id" value="{{ $student->id }}"/>
                        <input type="hidden" name="curp" value="{{ $student->curp }}"/>
                        <input type="hidden" name="birthdate" value="{{ $student->birthdate }}"/>
                        <input type="hidden" name="genre" value="{{ $student->genre }}"/>
                        <input type="hidden" name="course_id" value="{{ $student->course_id }}"/>
                        <input type="hidden" name="payment_periodicity_id" value="{{ $student->payment_periodicity_id }}"/>
                        <input type="hidden" name="start" value="{{ $student->start }}"/>
                        <input type="hidden" name="crew_id" value="{{ $student->crew_id }}"/>
                        <input type="hidden" name="name" value="{{ $student->name }}"/>
                        <input type="hidden" name="surnames" value="{{ $student->surnames }}"/>
                        <h5 class="text-orange"><b>Información general<hr></b></h5>
                        <b>Nombre: </b>{{ $student->surnames.', '.$student->name }}<br>
                        <div class="d-flex">
                            <div><b>Fecha de nacimiento: </b>{{ $student->birthdate }}</div>
                            <div class="ms-4"><b>Edad: </b>{{ $age }}</div>
                        </div>
                        <b>CURP: </b>{{ $student->curp }}<br>
                        <b>Dirección: </b><input class="form-control text-uppercase" name="address" type="text" value="{{ old('address',$student->address) }}"/> <br>
                        <b>Colonia: </b><input class="form-control text-uppercase" name="colony"  type="text" value="{{ old('colony',$student->colony) }}"/><br>
                        <b>Municipio: </b><input class="form-control text-uppercase" name="municipality"  type="text" value="{{ old('municipality',$student->municipality) }}"/><br>
                        <b>C.P.: </b><input class="form-control text-uppercase" name="pc"  type="text" value="{{old('pc', $student->PC )}}"/><br>
                        <b>Género: </b>@if($student->genre=="M") Mujer @elseif($student->genre=="H") Hombre @else No Binario @endif<br>
                        <b>Teléfono: </b><input class="form-control text-uppercase" name="phone"  type="text" value="{{ old('phone', $student->phone) }}"/><br>
                        <b>Celular: </b><input class="form-control text-uppercase" name="cel_phone" type="text" value="{{ old('cel_phone', $student->cel_phone) }}"/><br>
                        <b>Correo electrónico: </b><input class="form-control text-uppercase" name="email"  type="text" value="{{ old('email', $student->email) }}"/><br><br>
                        <h5 class="text-orange"><b>Información académica<hr></b></h5>
                        <b>Matrícula: </b>{{ $student->crew->name[0].'/'.$student->id }}<br>
                        <b>Curso: </b>{{ $student->course->name  }}<br>
                        <b>Tipo pago: </b>{{ $student->paymentPeriodicity->name  }}<br>
                        <div class="form-group">
                            <label for="exampleSelect"><b>Horario:</b></label>
                            <select class="form-control text-uppercase" name="schedule_id" id="schedule_id">
                                @foreach($schedules as $schedule)
                                    <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>{{ $schedule->name }}</option>
                                @endforeach
                            </select>
                        </div><br>
                        <div>
                            <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" value="false" class="btn-check text-uppercase" name="sabbatine" id="sabf" autocomplete="off" {{ old('sabbatine', $student->sabbatine == 0 ? 'false' : '') == 'false' ? 'checked' : '' }}>
                                <label class="btn btn-outline-orange text-uppercase" for="sabf">Intersemanal</label>

                                <input type="radio" value="true" class="btn-check text-uppercase" name="sabbatine" id="sabt" autocomplete="off" {{ old('sabbatine', $student->sabbatine == 1 ? 'true' : '') == 'true' ? 'checked' : '' }}>
                                <label class="btn btn-outline-orange text-uppercase" for="sabt">Sabatino</label>
                            </div>
                        </div><br>
                        <div class="d-flex align-items-center">
                            <b>Colegiatura: </b>
                            <span class="ms-2">${{ number_format($student->tuition ?? 0, 2) }}</span>
                            <button type="button" class="btn btn-outline-orange btn-sm ms-3" onclick="openModal('tuitionChangeModal')">Solicitar cambio</button>
                        </div><br>
                        <div class="form-group">
                            <label for="exampleSelect"><b>Modalidad:</b></label>
                            <select class="form-control text-uppercase" name="modality_id" id="modality_id">
                                @foreach($modalities as $modality)
                                    <option value="{{ $modality->id }}" {{ old('modality_id', $student->modality_id) == $modality->id ? 'selected' : '' }}>{{ $modality->name }}</option>
                                @endforeach
                            </select>
                        </div><br>
                        <b>Inicio: </b>
                        {{ $student->start }}<br><br>
                        <h5 class="text-orange"><b>Información de tutor<hr></b></h5>
                        <b>Nombre: </b><input class="form-control text-uppercase" name="tutor_name"  type="text" value="{{ old('tutor_name', $student->tutor->tutor_name)}}"/><br>
                        <b>Apellidos: </b><input class="form-control text-uppercase" name="tutor_surnames"  type="text" value="{{ old('tutor_surnames',$student->tutor->tutor_surnames)}}"/><br>
                        <b>Teléfono: </b><input class="form-control text-uppercase" name="tutor_phone"  type="text" value="{{ old('tutor_phone',$student->tutor->tutor_phone) }}"/><br>
                        <b>Celular: </b><input class="form-control text-uppercase" name="tutor_cel_phone"  type="text" value="{{ old('tutor_cel_phone',$student->tutor->tutor_cel_phone) }}"/><br>
                        <b>Parentesco: </b><input class="form-control text-uppercase" name="relationship"  type="text" value="{{ old('tutor_relationship',$student->tutor->relationship) }}"/><br><br>
                    <h5 class="text-orange"><b>Documentación<hr></b></h5>
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
                                        <a href="{{ route('system.student.document', ['student_id' => $student->id, 'document_id' => $document->id]) }}" target="_blank">
                                            @if ($isPdf)
                                                <i class="fas fa-file-pdf" style="font-size: 40px; color: #dc3545;"></i>
                                            @else
                                                <img src="{{ route('system.student.document', ['student_id' => $student->id, 'document_id' => $document->id]) }}" alt="{{ $document->name }}" style="max-width: 60px; max-height: 60px; border: 1px solid #ddd; border-radius: 4px; display: block; margin: 0 auto;">
                                            @endif
                                        </a>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if (!$document->pivot->uploaded)
                                        <button type="button" class="btn btn-outline-orange btn-sm" onclick="openDocumentModal({{ $document->id }}, '{{ $document->name }}')">Añadir documento</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </table><br>
                        <h5 class="text-orange"><b>Observaciones<hr></b></h5>
                        @foreach ($student->observations as $observation)
                            <div style="display: flex; margin-bottom: 10px; align-items: flex-start;">
                                <div style="width: 90px; font-weight: bold;">{{ $observation->created_at->format('d/m/Y') }}</div>
                                <div style="flex-grow: 1; margin-left: 40px; margin-right: 40px; text-align: justify;">{{ $observation->description }}</div>
                            </div>
                        @endforeach
                        <div class="mt-3">
                            <textarea class="form-control text-uppercase" name="observation" id="observation" rows="3" placeholder="Escribe una nueva observación..."></textarea>
                        </div>
                        <div class="d-flex justify-content-center mt-3"><button class="btn bg-orange text-white" type="submit" onclick="showLoader(true)">Actualizar datos</button></div><br><br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Solicitar Cambio de Colegiatura -->
<div class="custom-modal" id="tuitionChangeModal" onclick="closeOnOverlay(event, 'tuitionChangeModal')">
    <div class="custom-modal-content">
        <span class="custom-modal-close" onclick="closeModal('tuitionChangeModal')">&times;</span>
        <h5>Solicitar cambio de colegiatura</h5>
        <form method="POST" action="{{ route('system.student.request-tuition-change') }}">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">
            <div class="mb-3">
                <label class="form-label"><b>Colegiatura actual:</b></label>
                <p>${{ number_format($student->tuition ?? 0, 2) }}</p>
            </div>
            <div class="mb-3">
                <label for="new_tuition" class="form-label"><b>Nueva colegiatura:</b></label>
                <input type="number" class="form-control" id="new_tuition" name="new_tuition" step="0.01" min="0.01" required>
            </div>
            <div class="mb-3">
                <label for="reason" class="form-label"><b>Motivo del cambio:</b></label>
                <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
            </div>
            <div class="mt-4 text-end">
                <button type="button" class="btn btn-secondary me-2" onclick="closeModal('tuitionChangeModal')">Cancelar</button>
                <button type="submit" class="btn bg-orange text-white">Enviar solicitud</button>
            </div>
        </form>
    </div>
</div>
<!-- Modal Añadir Documento -->
<div class="custom-modal" id="documentModal" onclick="closeOnOverlay(event, 'documentModal')">
    <div class="custom-modal-content">
        <span class="custom-modal-close" onclick="closeModal('documentModal')">&times;</span>
        <h5 id="documentModalTitle">Añadir documento</h5>
        <form method="POST" action="{{ route('system.student.upload-document') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">
            <input type="hidden" name="document_id" id="document_id">
            <div class="mb-3">
                <label for="document_file" class="form-label"><b>Selecciona el archivo:</b></label>
                <input type="file" class="form-control" id="document_file" name="document_file" accept="image/*,.pdf" required>
                <small class="form-text text-muted">Formatos permitidos: Imágenes (JPG, PNG, etc.) y PDF</small>
            </div>
            <div class="mt-4 text-end">
                <button type="button" class="btn btn-secondary me-2" onclick="closeModal('documentModal')">Cancelar</button>
                <button type="submit" class="btn bg-orange text-white">Subir documento</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
