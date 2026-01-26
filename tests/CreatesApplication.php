<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{

    public function createApplication(): Application
    {
        if (!isset($_ENV['APP_KEY']) || $_ENV['APP_KEY'] === '') {
            $key = 'base64:' . base64_encode(random_bytes(32));
            $_ENV['APP_KEY'] = $key;
            $_SERVER['APP_KEY'] = $key;
            putenv("APP_KEY={$key}");
        }

        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
