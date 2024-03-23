@extends('layout.mainLayout')
@section('title','Informes')
@section('content')
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
                        <h5 class="text-orange"><b>Información general<hr></b></h5>
                        <b>Nombre: </b>{{ $student->surnames.', '.$student->name }}<br>
                        <b>Fecha de nacimiento:</b>{{ $student->birthdate }}<br>
                        <b>CURP: </b>{{ $student->curp }}<br>
                        <b>Dirección: </b><input class="form-control" type="text" value="{{ $student->address }}"/> <br>
                        <b>Colonia: </b><input class="form-control"  type="text" value="{{ $student->colony }}"/><br>
                        <b>Municipio: </b><input class="form-control"  type="text" value="{{ $student->municipality }}"/><br>
                        <b>C.P.: </b><input class="form-control"  type="text" value="{{ $student->PC }}"/><br>
                        <b>Género: </b>@if($student->genre=="M") Mujer @elseif($student->genre=="H") Hombre @else No Binario @endif<br>
                        <b>Teléfono: </b><input class="form-control"  type="text" value="{{ $student->phone }}"/><br>
                        <b>Celular: </b><input class="form-control"  type="text" value="{{ $student->cel_phone }}"/><br>
                        <b>Correo electrónico: </b><input class="form-control"  type="text" value="{{ $student->email }}"/><br><br>
                        <h5 class="text-orange"><b>Información académica<hr></b></h5>
                        <b>Curso: </b>{{ $student->course->name  }}<br>
                        <b>Tipo pago:</b>{{ $student->payment_periodicity_id  }}<br>
                        <b>Colegiatura: </b>{{ $student->cel_phone }}<br>
                        <b>Horario: </b><input class="form-control"  type="text" value="{{ $student->tutor_id}}"/><br>
                        <b>Sabatino: </b><input class="form-control"  type="text" value="{{ $student->cel_phone }}"/><br>
                        <b>Modalidad: </b><input class="form-control"  type="text" value="{{ $student->tutor_id}}"/><br>
                        <b>Inicio: </b>
                        {{ $student->start }}<br><br>
                        <h5 class="text-orange"><b>Información de tutor<hr></b></h5>
                        <b>Nombre: </b><input class="form-control"  type="text" value="{{ $student->tutor_id}}"/><br>
                        <b>Apellidos: </b><input class="form-control"  type="text" value="{{ $student->tutor_id}}"/><br>
                        <b>Teléfono: </b><input class="form-control"  type="text" value="{{ $student->tutor_id }}"/><br>
                        <b>Celular: </b><input class="form-control"  type="text" value="{{ $student->tutor_id }}"/><br>
                        <b>Parentesco: </b><input class="form-control"  type="text" value="{{ $student->tutor_id }}"/><br><br>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
