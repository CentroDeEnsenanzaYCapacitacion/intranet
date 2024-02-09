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
                        <td class="d-flex justify-content-center">
                            <span class="material-symbols-outlined bg-edit"><a href="{{ route('admin.users.edit',['id' => $user->id]) }}">edit</a></span>
                            <form method="POST" action="{{ route('admin.users.block',['id' => $user->id]) }}" id="delete-user-{{ $user->id }}">
                                @csrf
                                @method('DELETE')
                                <span class="material-symbols-outlined bg-red"><a onclick="confirmDelete('user',{{ $user->id }})">block</a></span>
                            </form>
                        </td>                      
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('admin.users.new') }}"  class="btn bg-orange text-white mt-content">Nuevo Usuario</a>
    </div>
</div>
@endsection
