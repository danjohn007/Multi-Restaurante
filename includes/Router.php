<?php
/**
 * Router Class for handling URL routing
 */
class Router {
    private $routes = [];
    private $baseUrl;
    
    public function __construct() {
        $this->baseUrl = BASE_URL;
    }
    
    public function get($pattern, $callback) {
        $this->addRoute('GET', $pattern, $callback);
    }
    
    public function post($pattern, $callback) {
        $this->addRoute('POST', $pattern, $callback);
    }
    
    private function addRoute($method, $pattern, $callback) {
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'callback' => $callback
        ];
    }
    
    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $_SERVER['REQUEST_URI'];
        
        // Remove base URL from request URI
        $baseUrlPath = parse_url($this->baseUrl, PHP_URL_PATH);
        if ($baseUrlPath && strpos($requestUri, $baseUrlPath) === 0) {
            $requestUri = substr($requestUri, strlen($baseUrlPath));
        }
        
        // Remove query string
        $requestUri = strtok($requestUri, '?');
        
        // Remove leading slash
        $requestUri = ltrim($requestUri, '/');
        
        // Default route
        if (empty($requestUri)) {
            $requestUri = 'home/index';
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod) {
                $pattern = str_replace('/', '\/', $route['pattern']);
                $pattern = '/^' . $pattern . '$/';
                
                if (preg_match($pattern, $requestUri, $matches)) {
                    array_shift($matches); // Remove full match
                    return $this->callCallback($route['callback'], $matches);
                }
            }
        }
        
        // Try to match controller/action pattern
        $this->matchControllerAction($requestUri);
    }
    
    private function matchControllerAction($uri) {
        $parts = explode('/', $uri);
        $controllerName = isset($parts[0]) ? ucfirst($parts[0]) : 'Home';
        $actionName = isset($parts[1]) ? $parts[1] : 'index';
        $params = array_slice($parts, 2);
        
        $controllerFile = __DIR__ . '/controllers/' . $controllerName . 'Controller.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controllerClass = $controllerName . 'Controller';
            
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                
                if (method_exists($controller, $actionName)) {
                    call_user_func_array([$controller, $actionName], $params);
                    return;
                }
            }
        }
        
        // 404 - Not found
        $this->show404();
    }
    
    private function callCallback($callback, $params = []) {
        if (is_callable($callback)) {
            call_user_func_array($callback, $params);
        } else if (is_string($callback)) {
            $parts = explode('@', $callback);
            if (count($parts) === 2) {
                $controllerName = $parts[0];
                $actionName = $parts[1];
                
                $controllerFile = __DIR__ . '/controllers/' . $controllerName . '.php';
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    $controller = new $controllerName();
                    call_user_func_array([$controller, $actionName], $params);
                }
            }
        }
    }
    
    private function show404() {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>The requested page could not be found.</p>";
        echo "<a href='" . BASE_URL . "'>Go Home</a>";
    }
}
?>