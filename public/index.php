<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../bootstrap/env.php';

use App\Controllers\ItemController;
use App\Http\Request;
use App\Router\Router;
use App\Services\MealDbClient;

$router = new Router();

$mealDb = new MealDbClient();
$itemsController = new ItemController($mealDb);

$router->get('/api/items', [$itemsController, 'list']);

// health route
$router->get('/health', function () {
    return ['status' => 'ok', 'app' => $_ENV['APP_NAME'] ?? 'Asian Store API'];
});

$request = Request::fromGlobals();
$response = $router->dispatch($request);
$response->send();


