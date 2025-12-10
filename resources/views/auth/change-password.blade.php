@extends('layout.mainLayout')

@section('title', 'Cambiar contraseña')

@section('content')
<div class="card shadow ccont pb-3">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>Cambiar contraseña</h1>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('password.change.update') }}" id="changePasswordForm">
            @csrf
            <div class="row d-flex text-center mt-5">
                <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
                    <div class="form-group">
                        <label for="current_password"><b>Contraseña actual:</b></label>
                        <input type="password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               id="current_password"
                               name="current_password"
                               placeholder="Contraseña actual"
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password"><b>Nueva contraseña:</b></label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Nueva contraseña"
                               required>
                        <small class="form-text text-muted">
                            Debe tener al menos 8 caracteres y ser diferente a la actual.
                        </small>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation"><b>Confirmar nueva contraseña:</b></label>
                        <input type="password"
                               class="form-control"
                               id="password_confirmation"
                               name="password_confirmation"
                               placeholder="Confirmar nueva contraseña"
                               required>
                    </div>
                </div>
            </div>

            <div class="row d-flex text-center mt-content">
                <div class="col">
                    <button type="submit" onclick="showLoader(true)" class="btn bg-orange text-white w-25">
                        Cambiar contraseña
                    </button><br><br>
                    <a href="{{ route('dashboard') }}">
                        <button type="button" class="btn btn-outline-orange text-white w-25">Volver</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
<br>
@endsection

@push('scripts')
<script>
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmation = document.getElementById('password_confirmation').value;

        if (password !== confirmation) {
            e.preventDefault();
            alert('Las contraseñas no coinciden.');
            return false;
        }

        if (password.length < 8) {
            e.preventDefault();
            alert('La contraseña debe tener al menos 8 caracteres.');
            return false;
        }
    });
</script>
@endpush
