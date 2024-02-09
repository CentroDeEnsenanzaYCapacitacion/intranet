<div class="container-fluid header-fixed">
    <div class="row bg-orange navbar">
        <div class="col-12 col-lg-auto text-white d-flex justify-content-between justify-content-lg-start">
            <h2 class="mb-0">Bienvenid@ <?php echo Auth::user()->name.' '. Auth::user()->crew_id ?></h2>
        </div>
        <div class="col-12 col-lg-auto ml-lg-auto d-flex align-items-center">
            <a href="javascript:history.back()"><img class="header_icon" src="{{asset('assets/img/back.png')}}"></a>
            <a href="{{route('adminFunctions')}}"><img class="header_icon" src="{{asset('assets/img/home.png')}}"></a>
            <a href="{{ route('logout') }}"><img class="header_icon" src="{{asset('assets/img/exit.png')}}" style="margin-right: 10px;"></a>
        </div>
    </div>
</div>