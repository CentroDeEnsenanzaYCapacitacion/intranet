@extends('layout.mainLayout')
@section('title','usuarios registrados')
@section('content')
<div class="row d-flex text-center mt-content">
    <div class="col">
        <h1>Usuarios registrados</h1>
    </div>
</div>
<div class="row d-flex text-center mt-5">
    <div class="col">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Celular</th>
                        <th>Género</th>
                        <th>Rol</th>
                        <th>Plantel</th>
                        <th style="width: 10%;" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($users as $user)
                            <td class="text-uppercase">{{$user->name}}</td>
                            <td class="text-uppercase">{{$user->surnames}}</td>
                            <td class="text-uppercase">{{$user->email}}</td>
                            <td class="text-uppercase">{{$user->phone}}</td>
                            <td class="text-uppercase">{{$user->cel_phone}}</td>
                            <td class="text-uppercase">{{$user->genre}}</td>
                            <td class="text-uppercase">{{$user->role->name}}</td>
                            <td class="text-uppercase">{{$user->crew->name}}</td>
                            <td class="text-center">
                                <span class="material-symbols-outlined bg-edit"><a class="editButton" href="javascript:void(0);" data-user-id="'.$user->id.'">edit</a></span>
                                <span class="material-symbols-outlined bg-red"><a class="blockButton" href="javascript:void(0);" data-user-id="'.$user->id.'">block</a></span>
                            </td>                      
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        <a href="{{ route('admin.users.new') }}"  class="btn bg-orange text-white mt-content">Nuevo Usuario</a>
    </div>
</div>
@endsection
