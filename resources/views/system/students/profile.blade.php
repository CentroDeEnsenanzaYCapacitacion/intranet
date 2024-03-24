@extends('layout.mainLayout')
@section('title','Informes')
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
                        <img src="{{ asset('assets/img/nophoto.jpg') }}">
                    </div>
                </div>
                <button class="btn bg-orange text-white">Cambiar Fotografía</button>
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
                            <div class="ms-4"><b>Edad: </b>{{ $edad }}</div>
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
                        <b>Colegiatura: </b>${{ $amount->amount }}<br>
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
                        <div class="d-flex justify-content-center"><button class="btn bg-orange text-white" type="submit" onclick="showLoader(true)">Actualizar datos</button></div><br><br>
                    </form>
                    <h5 class="text-orange"><b>Documentación<hr></b></h5>
                        <table class="table table-sm">
                            @foreach ($student->documents as $document)
                            <tr>
                                <td>{{ $document->name }}</td>
                                <td>
                                    @if ($document->pivot->uploaded)
                                        <span class="badge bg-success">&nbsp;</span>
                                    @else
                                        <span class="badge bg-danger">&nbsp;</span>
                                    @endif
                                </td>
                                <td></td>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
