<?php
spl_autoload_register();

use App\Contracts\Controller;
use App\Contracts\RoutesReader;

class Router
{
    public function __construct(private RoutesReader $routsReader)
    {
    }

    public function resolvePath()
    {
        $routes = $this->routsReader->getRoutes();
        $requestUrl = $_SERVER['REQUEST_URI'];
        try {
            foreach ($routes as $route) {
                $cmpRes = preg_match($route->regexp, $requestUrl);

                if ($cmpRes) {
                    $this->callController(new $route->controllerClassName);
                    break;
                }
            }
        } catch (Exception $error) {
            echo $error->getMessage();
        }
    }

    private static function callController(Controller $controller)
    {
        $controller->resolve();
    }
}

$routsReader = new App\Tools\TxtRouterReader($_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . 'routerConfig.txt');
$router = new Router($routsReader);

$router->resolvePath();
