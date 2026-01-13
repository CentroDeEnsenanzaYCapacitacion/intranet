@extends('layout.mainLayout')
@section('title', 'usuarios registrados')
@section('content')
    <div class="dashboard-welcome">
        <h1 class="dashboard-title">Gestión de Usuarios</h1>
        <p class="dashboard-subtitle">Administra cuentas y permisos de acceso</p>
    </div>

    <div class="modern-card">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                    <path d="M23 21V19C22.9993 18.1137 22.7044 17.2528 22.1614 16.5523C21.6184 15.8519 20.8581 15.3516 20 15.13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 3.13C16.8604 3.35031 17.623 3.85071 18.1676 4.55232C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89318 18.7122 8.75608 18.1676 9.45769C17.623 10.1593 16.8604 10.6597 16 10.88" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h2>Usuarios Activos</h2>
            </div>
            <a href="{{ route('admin.users.new') }}" class="btn-modern btn-primary" onclick="showLoader(true)">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Nuevo Usuario
            </a>
        </div>

        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Apellidos</th>
                        <th>Email</th>
                        <th class="text-center">Teléfono</th>
                        <th class="text-center">Celular</th>
                        <th class="text-center">Género</th>
                        <th class="text-center">Rol</th>
                        <th class="text-center">Plantel</th>
                        <th class="text-center" style="width: 120px;">Último acceso</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="text-uppercase font-medium text-center">{{ $user->name }}</td>
                            <td class="text-uppercase text-center">{{ $user->surnames }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">{{ $user->phone }}</td>
                            <td class="text-center">{{ $user->cel_phone }}</td>
                            <td class="text-uppercase text-center">{{ $user->genre }}</td>
                            <td class="text-center">
                                <span class="badge badge-primary">{{ $user->role->name }}</span>
                            </td>
                            <td class="text-center">{{ $user->crew->name }}</td>
                            <td class="text-center">
                                @if ($user->last_login)
                                    <span class="text-muted" style="font-size: 13px;">{{ $user->last_login->format('d/m/Y H:i') }}</span>
                                @else
                                    <span class="badge badge-gray">Sin acceso</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.users.edit', ['id' => $user->id]) }}"
                                       class="action-btn action-edit"
                                       onclick="showLoader(true)"
                                       title="Editar usuario">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M18.5 2.50001C18.8978 2.10219 19.4374 1.87869 20 1.87869C20.5626 1.87869 21.1022 2.10219 21.5 2.50001C21.8978 2.89784 22.1213 3.4374 22.1213 4.00001C22.1213 4.56262 21.8978 5.10219 21.5 5.50001L12 15L8 16L9 12L18.5 2.50001Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                    @if ($user->invitation && !$user->invitation->used)
                                        <form method="POST" action="{{ route('admin.users.resend-invitation', ['id' => $user->id]) }}"
                                              id="resend-invitation-{{ $user->id }}" class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                    class="action-btn action-warning"
                                                    onclick="showLoader(true)"
                                                    title="Reenviar invitación">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C9.5 21 7.25 20 5.5 18.25" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    <path d="M3 17L3 12L8 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.users.block', ['id' => $user->id]) }}"
                                          id="delete-user-{{ $user->id }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="action-btn action-delete"
                                                onclick="confirmDelete('user',{{ $user->id }})"
                                                title="Bloquear usuario">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                                <path d="M4.93 4.93L19.07 19.07" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modern-card" style="margin-bottom: 24px;">
        <div class="card-header-modern">
            <div class="header-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                    <path d="M4.93 4.93L19.07 19.07" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <h2>Usuarios Bloqueados</h2>
            </div>
        </div>

        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Apellidos</th>
                        <th>Email</th>
                        <th class="text-center">Teléfono</th>
                        <th class="text-center">Celular</th>
                        <th class="text-center">Género</th>
                        <th class="text-center">Rol</th>
                        <th class="text-center">Plantel</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($blocked_users as $user)
                        <tr>
                            <td class="text-uppercase font-medium text-center">{{ $user->name }}</td>
                            <td class="text-uppercase text-center">{{ $user->surnames }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">{{ $user->phone }}</td>
                            <td class="text-center">{{ $user->cel_phone }}</td>
                            <td class="text-uppercase text-center">{{ $user->genre }}</td>
                            <td class="text-center">
                                <span class="badge badge-gray">{{ $user->role->name }}</span>
                            </td>
                            <td class="text-center">{{ $user->crew->name }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.users.activate', ['id' => $user->id]) }}"
                                       class="action-btn action-success"
                                       onclick="showLoader(true)"
                                       title="Activar usuario">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21.5 2V22M21.5 2L16 7.5M21.5 2L16 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M2.5 22V2M2.5 22L8 16.5M2.5 22L8 16.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
