@extends('layout.mainLayout')
@section('title','usuarios registrados')
@section('content')
<div class="card shadow ccont">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>Usuarios registrados</h1>
            </div>
        </div>
        <div class="row d-flex text-center mt-5">
            <div class="col">
                <table class="table">
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
                        @foreach ($users as $user)
                        <tr>
                            <td class="text-uppercase">{{$user->name}}</td>
                            <td class="text-uppercase">{{$user->surnames}}</td>
                            <td class="text-uppercase">{{$user->email}}</td>
                            <td class="text-uppercase">{{$user->phone}}</td>
                            <td class="text-uppercase">{{$user->cel_phone}}</td>
                            <td class="text-uppercase">{{$user->genre}}</td>
                            <td class="text-uppercase">{{$user->role->name}}</td>
                            <td class="text-uppercase">{{$user->crew->name}}</td>
                            <td class="justify-content-center">
                                <span class="material-symbols-outlined bg-edit"><a onclick="showLoader(true)" href="{{ route('admin.users.edit',['id' => $user->id]) }}">edit</a></span>
                                <form method="POST" action="{{ route('admin.users.block',['id' => $user->id]) }}" id="delete-user-{{ $user->id }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <span class="material-symbols-outlined bg-red"><a onclick="confirmDelete('user',{{ $user->id }})">block</a></span>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <a href="{{ route('admin.users.new') }}"  class="btn bg-orange text-white mt-5 mb-5">Nuevo Usuario</a>
            </div>
        </div>
        <div class="row d-flex text-center mt-content">
            <div class="col">
                <h1>Usuarios bloqueados</h1>
            </div>
        </div>
        <div class="row d-flex text-center mt-5">
            <div class="col">
                <table class="table">
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
                        @foreach ($blocked_users as $user)
                        <tr>
                            <td class="text-uppercase">{{$user->name}}</td>
                            <td class="text-uppercase">{{$user->surnames}}</td>
                            <td class="text-uppercase">{{$user->email}}</td>
                            <td class="text-uppercase">{{$user->phone}}</td>
                            <td class="text-uppercase">{{$user->cel_phone}}</td>
                            <td class="text-uppercase">{{$user->genre}}</td>
                            <td class="text-uppercase">{{$user->role->name}}</td>
                            <td class="text-uppercase">{{$user->crew->name}}</td>
                            <td class="justify-content-center">
                                <span class="material-symbols-outlined bg-edit"><a onclick="showLoader(true)" href="{{ route('admin.users.activate',['id' => $user->id]) }}">restart_alt</a></span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
