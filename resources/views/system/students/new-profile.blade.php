@extends('layout.mainLayout')
@section('title', 'Informes')
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
                            <img src="{{ route('system.student.image', ['student_id' => $student->id]) }}" alt="Profile Image">
                        </div>
                    </div>
                    <a href="{{ route('system.student.profile-image', ['student_id' => $student->id]) }}"><button
                            class="btn bg-orange text-white">Cambiar Fotografía</button></a>
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
                                value="{{ old('birthdate') }}">
                            <br>
                            <b>CURP: </b><input class="form-control text-uppercase" name="curp" type="text"
                                value="{{ old('curp') }}" /><br>
                            <b>Dirección: </b><input class="form-control text-uppercase" type="text" name="address"
                                value="{{ old('address') }}" /> <br>
                            <b>Colonia: </b><input class="form-control text-uppercase" type="text" name="colony"
                                value="{{ old('colony') }}" /><br>
                            <b>Municipio: </b><input class="form-control text-uppercase" type="text" name="municipality"
                                value="{{ old('municipality') }}" /><br>
                            <b>C.P.: </b><input class="form-control text-uppercase" type="text" name="pc"
                                value="{{ old('pc') }}" /><br>
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
                                value="{{ old('phone') }}" /><br>
                            <b>Celular: </b><input class="form-control text-uppercase" type="text" name="cel_phone"
                                value="{{ old('cel_phone') }}" /><br>
                            <b>Correo electrónico: </b><input class="form-control text-uppercase" type="text"
                                name="email" value="{{ old('email', $student->email) }}" /><br><br>
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
                                value="{{ old('start') }}"><br><br>
                            <h5 class="text-orange"><b>Información de tutor
                                    <hr>
                                </b></h5>
                            <b>Nombre: </b><input class="form-control text-uppercase" type="text" name="tutor_name"
                                value="{{ old('tutor_name') }}" /><br>
                            <b>Apellidos: </b><input class="form-control text-uppercase" type="text"
                                name="tutor_surnames" value="{{ old('tutor_surnames') }}" /><br>
                            <b>Teléfono: </b><input class="form-control text-uppercase" type="text" name="tutor_phone"
                                value="{{ old('tutor_phone') }}" /><br>
                            <b>Celular: </b><input class="form-control text-uppercase" type="text"
                                name="tutor_cel_phone" value="{{ old('tutor_cel_phone') }}" /><br>
                            <b>Parentesco: </b><input class="form-control text-uppercase" type="text"
                                name="relationship" value="{{ old('relationship') }}" /><br>
                            <div class="d-flex justify-content-center"><button class="btn bg-orange text-white"
                                    type="submit" onclick="showLoader(true)">Guardar</button></div><br><br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
