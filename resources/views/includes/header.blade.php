<div class="container-fluid header-fixed shadow">
    <div class="row bg-orange navbar">
        <div class="col-12 col-lg-auto text-white d-flex justify-content-between justify-content-lg-start">
            <h2 class="mb-0">Bienvenid@ {{ Auth::user()->name }} @if(Auth::user()->crew_id !=1){{ Auth::user()->crew->name }} @endif - {{ Auth::user()->role->name }}</h2>
        </div>
        <div class="col-12 col-lg-auto ml-lg-auto d-flex align-items-center">

            <a href="{{route('dashboard')}}"><img class="header_icon" src="{{asset('assets/img/home.png')}}"></a>
            <a href="{{ route('password.change') }}" title="Cambiar contraseña">
                <i class="fas fa-key header_icon" style="color: white; font-size: 24px; margin: 0 10px;"></i>
            </a>
            <a href="{{ route('logout') }}"><img class="header_icon" src="{{asset('assets/img/exit.png')}}" style="margin-right: 10px;"></a>
        </div>
    </div>
</div>
