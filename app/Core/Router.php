<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private string $currentMethod = '';
    private string $currentPath = '';
    private array $pathVariables = [];

    public function get(string $path, array|string $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array|string $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function put(string $path, array|string $handler): void
    {
        $this->routes['PUT'][$path] = $handler;
    }

    public function delete(string $path, array|string $handler): void
    {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function dispatch(): void
    {
        $this->currentMethod = $_SERVER['REQUEST_METHOD'];
        $this->currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Strip BASE_PATH from the beginning
        $this->currentPath = str_replace(BASE_PATH, '', $this->currentPath) ?: '/';
        
        // Also strip 'public/' if it's at the beginning (from .htaccess rewrite)
        if (strpos($this->currentPath, 'public/') === 0) {
            $this->currentPath = substr($this->currentPath, 7); // 7 = strlen('public/')
        }
        
        // Ensure path starts with /
        if (empty($this->currentPath) || $this->currentPath[0] !== '/') {
            $this->currentPath = '/' . $this->currentPath;
        }

        if (!isset($this->routes[$this->currentMethod])) {
            $this->notFound();
            return;
        }

        foreach ($this->routes[$this->currentMethod] as $path => $handler) {
            if ($this->matchPath($path)) {
                $this->callHandler($handler);
                return;
            }
        }

        $this->notFound();
    }

    private function matchPath(string $path): bool
    {
        $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_-]+)', $path);
        $pattern = str_replace('/', '\/', $pattern);
        
        if (preg_match("/^{$pattern}$/", $this->currentPath, $matches)) {
            array_shift($matches);
            
            preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $path, $paramNames);
            foreach ($paramNames[1] ?? [] as $index => $paramName) {
                $this->pathVariables[$paramName] = $matches[$index] ?? null;
            }

            return true;
        }

        return false;
    }

    private function callHandler(array|string $handler): void
    {
        if (is_string($handler)) {
            [$controller, $action] = explode('@', $handler);
            $controller = "App\\Controllers\\{$controller}";
            $instance = new $controller();
            $instance->$action();
        } elseif (is_array($handler)) {
            [$controller, $action] = $handler;
            $instance = new $controller();
            $instance->$action();
        }
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo "404 - Not Found";
        exit;
    }

    public function getPathVariable(string $name): ?string
    {
        return $this->pathVariables[$name] ?? null;
    }
}
