<?php
// Start a Session
if( !session_id() ) {session_start();}

require_once "../vendor/autoload.php";
//require_once "vendor/autoload.php";
use DI\ContainerBuilder;
use League\Plates\Engine;
use Delight\Auth\Auth;

$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions([
    Engine::class =>function() {
        return new Engine('../app/views');
    },
    PDO::class => function() {
        $driver = "mysql";
        $host = "localhost";
        $database_name = "ooproject";
        $username = "root";
        $password = "";

        return new PDO("$driver:host=$host;dbname=$database_name", $username, $password);
    },
    Auth::class => function($container) {
        return new Auth($container->get('PDO'));
    }
]);
$container = $containerBuilder->build();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\controllers\HomeController', 'index']);
    $r->addRoute('GET', '/create', ['App\controllers\HomeController', 'create']);
    $r->addRoute('POST', '/createform', ['App\controllers\HomeController', 'createForm']);
    $r->addRoute('GET', '/edit', ['App\controllers\HomeController', 'edit']);
    $r->addRoute('POST', '/editform', ['App\controllers\HomeController', 'editForm']);
    $r->addRoute('GET', '/profile', ['App\controllers\HomeController', 'profile']);
    $r->addRoute('GET', '/register', ['App\controllers\HomeController', 'register']);
    $r->addRoute('POST', '/regform', ['App\controllers\HomeController', 'regForm']);
    $r->addRoute('GET', '/verification', ['App\controllers\HomeController', 'emailVerifie']);
    $r->addRoute('POST', '/loginform', ['App\controllers\HomeController', 'loginForm']);
    $r->addRoute('GET', '/logout', ['App\controllers\HomeController', 'logout']);
    $r->addRoute('GET', '/security', ['App\controllers\HomeController', 'security']);
    $r->addRoute('POST', '/secform', ['App\controllers\HomeController', 'secForm']);
    $r->addRoute('GET', '/status', ['App\controllers\HomeController', 'status']);
    $r->addRoute('POST', '/statusform', ['App\controllers\HomeController', 'statusForm']);
    $r->addRoute('GET', '/users', ['App\controllers\HomeController', 'users']);
    $r->addRoute('GET', '/umedia', ['App\controllers\HomeController', 'umedia']);
    $r->addRoute('POST', '/umediaform', ['App\controllers\HomeController', 'umediaForm']);
    $r->addRoute('GET', '/delete', ['App\controllers\HomeController', 'delete']);
    $r->addRoute('GET', '/role', ['App\controllers\HomeController', 'role']);
    $r->addRoute('GET', '/test', ['App\controllers\HomeController', 'test']);
    // {id} must be a number (\d+)
    //$r->addRoute('GET', '/user/{id:\d+}', ['App\controllers\HomeController', 'index']);
    // The /{title} suffix is optional
    //$r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo '404';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo '405';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $container->call($routeInfo[1], $routeInfo[2]);
        break;
}

?>