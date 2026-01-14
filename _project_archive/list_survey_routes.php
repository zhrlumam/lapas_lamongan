<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (Route::getRoutes() as $route) {
    if (str_contains((string)$route->getName(), 'survey')) {
        echo $route->getName() . ' -> ' . $route->uri() . "\n";
    }
}
