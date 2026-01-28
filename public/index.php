<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Controllers\MainController;
use Smarty\Smarty;
use App\Http\Request;
use App\Controllers\CategoryController;
use App\Controllers\PostController;

$smarty = new Smarty();
$smarty->setTemplateDir(__DIR__ . '/../templates');
$smarty->setCompileDir(__DIR__ . '/../templates_c');

$request = new Request();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($path, '/');

$routes = [
    '' => function() use ($smarty, $request) {
        $controller = new MainController($smarty);
        $controller->index();
    },
    'index.php' => function() use ($smarty, $request) {
        $controller = new MainController($smarty);
        $controller->index();
    },
    'category.php' => function() use ($smarty, $request) {
        $controller = new CategoryController($smarty, $request);
        $controller->show();
    },
    'post.php' => function() use ($smarty, $request) {
        $controller = new PostController($smarty, $request);
        $controller->show();
    }
];

if (isset($routes[$path])) {
    $routes[$path]();
} else {
    http_response_code(404);
    $smarty->display('404.tpl');
}
