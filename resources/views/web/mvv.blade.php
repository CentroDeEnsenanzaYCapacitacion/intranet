@extends('layout.mainLayout')
@section('title', 'dashboard')
@section('content')
@if (session('success'))
<div id="success" class="alert alert-success" style="margin-top: 100px;">
    {{ session('success') }}
</div>
@endif
<div class="card shadow ccont">
    <div class="card-body">
        <div class="row d-flex text-center mt-3 mb-3">
            <div class="col">
                <h1>Nosotros, Misión, Visión y Valores</h1>
            </div>
        </div>
        <div class="content ml-5">
            <form id="form" action="{{ route('web.mvv.post') }}" method="post" enctype="multipart/form-data">
                @csrf
                <table class="table table-borderless" width="100%">
                    <tbody>
                        @foreach($data as $index => $item)
                        <tr>
                            <td width="5%" style="vertical-align: middle;">
                                {{ $item->name }}
                            </td>
                            <td width="95%" style="text-align: left;vertical-align: middle;">
                                Caracteres restantes:
                                <input style="border: none; background-color:white" disabled maxlength="3" size="3" value="{{ 355 - strlen($item->description) }}" id="counter{{ $index + 1 }}">
                                <textarea onkeyup="textCounter(this, 'counter{{ $index + 1 }}', 355);" id="description{{ $index + 1 }}" maxlength="355" class="form-control" name="description{{ $index + 1 }}" rows="5" cols="25">{{ old('description' . ($index + 1), $item->description) }}</textarea>
                                @error('description' . ($index + 1))
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <table width="100%">
                    <tr>
                        <td style="text-align: center;">
                            <button class="btn bg-orange text-white" type="submit">Guardar datos</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/text_counter.js') }}"></script>
@endpush
