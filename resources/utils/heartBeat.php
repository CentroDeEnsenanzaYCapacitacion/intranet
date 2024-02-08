<?php
    use Illuminate\Support\Facades\Session;

    if(session::has('active_session')) {
        session(['last_heartbeat' => time()]);

}
