<?php

class Router {
    private array $routes = [];

    public function add(string $method, string $pattern, string $controller, string $action): void {
        $this->routes[] = compact('method', 'pattern', 'controller', 'action');
    }

    public function dispatch(): void {
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri    = rtrim($uri, '/') ?: '/';
        $method = $_SERVER['REQUEST_METHOD'];

        // Strip base path if app is in a subdirectory
        $base = rtrim(BASE_PATH, '/');
        if ($base && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base)) ?: '/';
        }

        foreach ($this->routes as $route) {
            if (strtoupper($route['method']) !== $method && $route['method'] !== 'ANY') {
                continue;
            }

            $pattern = $this->patternToRegex($route['pattern']);
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // remove full match
                $ctrl   = new $route['controller']();
                $action = $route['action'];
                $ctrl->$action(...array_values($matches));
                return;
            }
        }

        // 404
        http_response_code(404);
        include __DIR__ . '/../Views/errors/404.php';
    }

    private function patternToRegex(string $pattern): string {
        $pattern = preg_replace('/\{[a-z_]+\}/', '([0-9]+)', $pattern);
        return '#^' . $pattern . '$#';
    }
}
