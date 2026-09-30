<?php

declare(strict_types=1);

namespace App\Core;

use Closure;
use ReflectionClass;
use ReflectionNamedType;
use RuntimeException;

class Router
{
    private array $routes = [];

    private array $dependencies = [];

    private array $middleware = [];

    public function setDependency(
        string $class,
        mixed $instance
    ): void {
        $this->dependencies[$class] = $instance;
    }

    public function setMiddleware(
        string $name,
        object $middleware
    ): void {
        $this->middleware[$name] = $middleware;
    }

    public function get(
        string $path,
        array|Closure $handler,
        array $middleware = []
    ): void {
        $this->addRoute(
            'GET',
            $path,
            $handler,
            $middleware
        );
    }

    public function post(
        string $path,
        array|Closure $handler,
        array $middleware = []
    ): void {
        $this->addRoute(
            'POST',
            $path,
            $handler,
            $middleware
        );
    }

    private function addRoute(
        string $method,
        string $path,
        array|Closure $handler,
        array $middleware = []
    ): void {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(
        string $method,
        string $uri
    ): mixed {
        $method = strtoupper($method);

        $path = parse_url(
            $uri,
            PHP_URL_PATH
        );

        if (
            $path === false ||
            $path === null ||
            $path === ''
        ) {
            $path = '/';
        }

        foreach ($this->routes as $route) {

            if ($route['method'] !== $method) {
                continue;
            }

            $parameters = $this->matchRoute(
                $route['path'],
                $path
            );

            if ($parameters === null) {
                continue;
            }

            $this->runMiddleware(
                $route['middleware']
            );

            return $this->executeHandler(
                $route['handler'],
                $parameters
            );
        }

        http_response_code(404);

        return '404 - Page Not Found';
    }

    private function matchRoute(
        string $routePath,
        string $requestPath
    ): ?array {
        $routePath = trim(
            $routePath,
            '/'
        );

        $requestPath = trim(
            $requestPath,
            '/'
        );

        if (
            $routePath === '' &&
            $requestPath === ''
        ) {
            return [];
        }

        $routeParts = $routePath === ''
            ? []
            : explode('/', $routePath);

        $requestParts = $requestPath === ''
            ? []
            : explode('/', $requestPath);

        if (
            count($routeParts) !==
            count($requestParts)
        ) {
            return null;
        }

        $parameters = [];

        foreach (
            $routeParts
            as $index => $routePart
        ) {
            $requestPart = $requestParts[$index];

            if (
                strlen($routePart) > 2 &&
                $routePart[0] === '{' &&
                $routePart[strlen($routePart) - 1] === '}'
            ) {
                $parameterName = substr(
                    $routePart,
                    1,
                    -1
                );

                $parameters[$parameterName] =
                    urldecode($requestPart);

                continue;
            }

            if ($routePart !== $requestPart) {
                return null;
            }
        }

        return $parameters;
    }

    private function runMiddleware(
        array $middlewareNames
    ): void {
        foreach ($middlewareNames as $middlewareName) {

            if (!isset($this->middleware[$middlewareName])) {
                throw new RuntimeException(
                    "Middleware not registered: {$middlewareName}"
                );
            }

            $this->middleware[$middlewareName]->handle();
        }
    }

    private function executeHandler(
        array|Closure $handler,
        array $parameters
    ): mixed {
        if ($handler instanceof Closure) {
            return $handler(
                ...array_values($parameters)
            );
        }

        [$class, $method] = $handler;

        $controller = $this->makeController(
            $class
        );

        return $controller->$method(
            ...array_values($parameters)
        );
    }

    private function makeController(
        string $class
    ): object {
        if (isset($this->dependencies[$class])) {
            return $this->dependencies[$class];
        }

        if (!class_exists($class)) {
            throw new RuntimeException(
                "Controller class not found: {$class}"
            );
        }

        $reflection = new ReflectionClass($class);

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $arguments = [];

        foreach (
            $constructor->getParameters()
            as $parameter
        ) {
            $type = $parameter->getType();

            if (
                !$type instanceof ReflectionNamedType ||
                $type->isBuiltin()
            ) {
                if ($parameter->isDefaultValueAvailable()) {
                    $arguments[] =
                        $parameter->getDefaultValue();

                    continue;
                }

                throw new RuntimeException(
                    "Cannot resolve dependency for "
                        . "{$class}::"
                        . $parameter->getName()
                );
            }

            $dependencyClass =
                $type->getName();

            if (
                !isset(
                    $this->dependencies[$dependencyClass]
                )
            ) {
                throw new RuntimeException(
                    "Dependency not registered: "
                        . $dependencyClass
                );
            }

            $arguments[] =
                $this->dependencies[$dependencyClass];
        }

        return $reflection->newInstanceArgs(
            $arguments
        );
    }
}
