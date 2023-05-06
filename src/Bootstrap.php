<?php declare(strict_types = 1);

namespace AdsJob;
use AdsJob\Database\DB;
require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$environment = 'development';


$whoops = new \Whoops\Run;
if($environment !== 'production') {
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
}else {
    $whoops->pushHandler(function($e){
            echo '
            <html>
                <head>
                    <title>Error</title>
                    <link rel="stylesheet" href="/light/css/error.css">
                </head>
                <body>
                    <div>
                        <h1>An error has occurred</h1>
                    </div>    
                </body>
            </html>
            ';
        //TODO: Email the dev
    });
}
$whoops->register();

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$injector = include('Dependencies.php');

$request = $injector->make('Http\HttpRequest');
$response = $injector->make('Http\HttpResponse');
DB::connect();

$routeDefinitionCallback = function (\FastRoute\RouteCollector $r) {
    $routes = include('Routes.php');
    foreach($routes as $route){
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};

$dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());
switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        $controller = $injector->make('\AdsJob\Controllers\ErrorController');
        $controller->error404();
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $controller = $injector->make('\AdsJob\Controllers\ErrorController');
        $controller->error405();
        break;
    case \FastRoute\Dispatcher::FOUND:
        $controllerName = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $vars = $routeInfo[2];

        $controller = $injector->make($controllerName);
        $controller->middleware();
        $passesMiddleware = true;
                
        foreach($controller->getMiddleware() as $middleware){
            try{
                $middleware->execute($method);
            }catch(\AdsJob\Exceptions\ForbiddenException $e){
                $response->redirect('/');
                $passesMiddleware = false;
                break;
            }catch(\AdsJob\Exceptions\AuthException $e){
                $response->redirect('/login');
                $passesMiddleware = false;
                break;
            }
        }
        if($passesMiddleware){
            $controller->$method($vars);
        }
        break;
}


foreach ($response->getHeaders() as $header) {
    header($header, false);
}

echo $response->getContent();
