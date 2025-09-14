<?php

class Router {
    private $routes = [];

    public function add($method, $path, $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch($method, $uri) {
        $method = strtoupper($method);
        foreach ($this->routes as $route) {
            $params = [];
            if ($this->matchRoute($route['path'], $uri, $params)) {
                if ($route['method'] === $method) {
                    if (is_callable($route['handler'])) {
                        return call_user_func_array($route['handler'], $params);
                    } elseif (is_array($route['handler']) && count($route['handler']) === 2) {
                        $controller = $route['handler'][0];
                        $methodName = $route['handler'][1];
                        if (is_object($controller) && method_exists($controller, $methodName)) {
                            return call_user_func_array([$controller, $methodName], $params);
                        }
                    }
                    throw new Exception("Handler inválido para rota: $uri");
                }
            }
        }
        http_response_code(404);
        echo "Página não encontrada";
    }

    private function matchRoute($routePath, $uri, &$params) {
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches); 
            $params = $matches;
            return true;
        }
        return false;
    }
}
