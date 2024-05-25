@extends('layout.mainLayout')
@section('title', 'Modificar costo')
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger mt-content">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div id="success" class="alert alert-success mt-content">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow ccont">
        <div class="card-body">
            <div class="row d-flex text-center mt-3">
                <div class="col mb-3">
                    <h1>Modificar costo</h1>
                </div>
                <div>
                    <h3>
                        {{ $amount->receiptType->name }}
                        @if ($amount->course)
                            {{ $amount->course->name }}
                        @endif
                        @if ($amount->crew_id != 1)
                            {{ $amount->crew->name }}
                        @endif
                    </h3>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <form action="{{ route('admin.catalogues.amount.update', ['id' => $amount->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="d-flex align-items-center justify-content-center">
                            <input class="form-control w-25" name="amount" type="text"
                                value="{{ old('amount', $amount->amount) }}">
                            <button class="btn bg-orange text-white ml-3" type="submit">Modificar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
