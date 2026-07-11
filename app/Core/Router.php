<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private array $middleware = [];
    private array $groupMiddleware = [];

    public function get(string $uri, array $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, array $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    public function put(string $uri, array $action): void
    {
        $this->addRoute('PUT', $uri, $action);
    }

    public function delete(string $uri, array $action): void
    {
        $this->addRoute('DELETE', $uri, $action);
    }

    public function addRoute(string $method, string $uri, array $action): void
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action,
            'middleware' => $this->groupMiddleware,
        ];
    }

    public function middleware(string $middleware): self
    {
        $this->groupMiddleware[] = $middleware;
        return $this;
    }

    public function group(callable $callback): void
    {
        $previous = $this->groupMiddleware;
        $callback($this);
        $this->groupMiddleware = $previous;
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri = $request->uri();

        foreach ($this->routes as $i => $route) {
            $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route['uri']);
            $pattern = '#^' . $pattern . '$#';

            if ($route['method'] !== $method) continue;

            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                foreach ($route['middleware'] as $middlewareClass) {
                    if (class_exists($middlewareClass)) {
                        $instance = new $middlewareClass();
                        $instance->handle($request, function () {});
                    }
                }

                [$controllerClass, $methodName] = $route['action'];

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    if (method_exists($controller, $methodName)) {
                        $controller->$methodName($request, ...array_values($params));
                        return;
                    }
                }
            }
        }

        http_response_code(404);
        self::renderError(404);
    }

    public static function renderError(int $code): void
    {
        $title = match ($code) {
            404 => 'Page Not Found',
            403 => 'Forbidden',
            500 => 'Server Error',
            default => 'Error',
        };

        http_response_code($code);
        $layout = $GLOBALS['_layout'] ?? 'main';

        ob_start();
        include __DIR__ . "/../Views/errors/{$code}.php";
        $content = ob_get_clean();

        $title = $title . ' - ' . config('app.name');
        include __DIR__ . "/../Views/layouts/{$layout}.php";
        exit;
    }
}
