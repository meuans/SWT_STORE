<?php

declare(strict_types=1);

namespace App\Router;

use App\Http\Request;
use App\Http\Response;

final class Router
{
    /** @var array<string, array<string, callable>> */
    private array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function put(string $path, callable $handler): void
    {
        $this->routes['PUT'][$path] = $handler;
    }

    public function delete(string $path, callable $handler): void
    {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function dispatch(Request $request): Response
    {
        $method = strtoupper($request->method);
        $handler = $this->routes[$method][$request->path] ?? null;

        if ($handler === null) {
            return Response::json(['error' => 'Not Found'], 404);
        }

        $result = $handler($request);

        if ($result instanceof Response) {
            return $result;
        }

        if (is_array($result)) {
            return Response::json($result);
        }

        if (is_string($result)) {
            return Response::text($result);
        }

        return Response::json(['error' => 'Invalid response'], 500);
    }
}
