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
                    <h5 class="text-orange"><b>Información general<hr></b></h5>
                    <b>Nombre: </b>{{ $student->surnames.', '.$student->name }}<br>
                    <b>Fecha de nacimiento: </b>{{ $student->birthdate }}<br>
                    <b>CURP: </b>{{ $student->curp }}<br>
                    <b>Edad: </b>{{ $student->birthdate }}<br>
                    <b>Dirección: </b>{{ $student->address }}<br>
                    <b>Colonia: </b>{{ $student->colony }}<br>
                    <b>Municipio: </b>{{ $student->municipality }}<br>
                    <b>C.P.: </b>{{ $student->PC }}<br>
                    <b>Género: </b>{{ $student->genre }}<br>
                    <b>Teléfono: </b>{{ $student->phone }}<br>
                    <b>Celular: </b>{{ $student->cel_phone }}<br>
                    <b>Correo electrónico: </b>{{ $student->email }}<br><br>
                    <h5 class="text-orange"><b>Información académica<hr></b></h5>
                    <b>Curso: </b>{{ $student->course->name }}<br>
                    <b>Tipo pago:</b>{{ $student->payment_periodicity_id }}<br>
                    <b>Colegiatura: </b><br>
                    <b>Horario: </b>{{ $student->schedule }}<br>
                    <b>Sabatino: </b><br>
                    <b>Modalidad: </b>{{ $student->modality_id }}<br>
                    <b>Inicio: </b>{{ $student->start }}<br><br>
                    <h5 class="text-orange"><b>Información de tutor<hr></b></h5>
                    <b>Nombre: </b>{{ $student->tutor_id.', '.$student->tutor_id }}<br>
                    <b>Teléfono: </b>{{ $student->tutor_id }}<br>
                    <b>Celular: </b>{{ $student->tutor_id }}<br>
                    <b>Parentesco: </b>{{ $student->tutor_id }}<br><br>
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
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
