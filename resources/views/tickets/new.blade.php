@extends('layout.mainLayout')
@section('title', 'Nuevo ticket de servicio')

@section('content')
<div class="card shadow ccont">
    <div class="card-body">
        <div class="row d-flex text-center mt-3">
            <div class="col">
                <h1>Reportar un nuevo problema</h1>
            </div>
        </div>

        <form action="{{ route('tickets.save') }}" method="POST" class="mt-4">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Título del ticket</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                @error('title')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descripción del problema (opcional)</label>
                <textarea name="description" id="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">Prioridad</label>
                <select name="priority" id="priority" class="form-select" required>
                    <option value="baja" {{ old('priority') == 'baja' ? 'selected' : '' }}>Baja</option>
                    <option value="media" {{ old('priority') == 'media' ? 'selected' : '' }}>Media</option>
                    <option value="alta" {{ old('priority') == 'alta' ? 'selected' : '' }}>Alta</option>
                    <option value="critica" {{ old('priority') == 'critica' ? 'selected' : '' }}>Crítica</option>
                </select>
                @error('priority')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Categoría</label>
                <select name="category_id" id="category_id" class="form-select">
                    <option value="" disabled selected>Selecciona una categoría</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="text-end">
                <a href="{{ route('tickets.list') }}" class="btn btn-secondary me-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Enviar ticket</button>
            </div>
        </form>
    </div>
</div>
@endsection
