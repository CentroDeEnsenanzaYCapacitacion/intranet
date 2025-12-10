<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../../../intranet/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../../../intranet/vendor/autoload.php';

$app = require_once __DIR__.'/../../../intranet/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
