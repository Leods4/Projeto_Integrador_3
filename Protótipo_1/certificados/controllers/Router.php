<?php
// /core/Router.php

class Router {
    public static function route() {
        $controller = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'CertificadoController';
        $action = $_GET['action'] ?? 'index';

        $controllerFile = __DIR__ . '/../controllers/' . $controller . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            if (class_exists($controller)) {
                $controllerInstance = new $controller();
                if (method_exists($controllerInstance, $action)) {
                    $controllerInstance->$action();
                    return;
                }
            }
        }

        http_response_code(404);
        echo "Página não encontrada.";
    }
}
