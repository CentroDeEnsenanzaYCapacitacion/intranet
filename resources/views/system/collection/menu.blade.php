@extends('layout.mainLayout')
@section('title','dashboard')
@section('content')
<div class="row d-flex align-items-center justify-content-center text-center mt-content">
    @if (in_array(Auth::user()->role_id, [1, 2, 3]))
    <div class="col-md-4 d-flex justify-content-center">
        <a id='reports' href="{{route('system.collection.tuition')}}">
            <div class="card align-content-center cc">
                <img class="menu_icon  d-block mx-auto" src="{{asset('assets/img/stpay.png')}}">
                <div class="card-body">
                    <h6 class="card-title">Cobros</h6>
                </div>
            </div>
        </a>
    </div>
    @endif
    @if (in_array(Auth::user()->role_id, [1, 2]))
    <div class="col-md-4 d-flex justify-content-center">
        <a id='profiles' href="{{route('system.collection.paybills')}}">
            <div class="card align-content-center cc">
                <img class="menu_icon  d-block mx-auto" src="{{asset('assets/img/spent.png')}}">
                <div class="card-body">
                    <h6 class="card-title">Pagos</h6>
                </div>
            </div>
        </a>
    </div>
    @endif
    {{-- <div class="col-md-4 d-flex justify-content-center">
        <a id='profiles' href="{{route('system.collection.menu')}}">
            <div class="card align-content-center cc">
                <img class="menu_icon  d-block mx-auto" src="{{asset('assets/img/pay.webp')}}">
                <div class="card-body">
                    <h6 class="card-title">Cobranza</h6>
                </div>
            </div>
        </a>
    </div> --}}
</div>
@endsection
