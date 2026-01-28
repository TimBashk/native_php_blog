<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Models\Category;
use Smarty\Smarty;

$smarty = new Smarty();
$smarty->setTemplateDir(__DIR__ . '/../templates');
$smarty->setCompileDir(__DIR__ . '/../templates_c');

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$path = trim($path, '/');

$routes = [
    '' => function() use ($smarty) { // главная
        $categories = Category::getWithPosts(3);
        $smarty->assign('categories', $categories);
        $smarty->display('index.tpl');
    },

    'index.php' => function() use ($smarty) { // главная через index.php
        $categories = Category::getWithPosts(3);
        $smarty->assign('categories', $categories);
        $smarty->display('index.tpl');
    },

    'category.php' => function() {
        include __DIR__ . '/category.php';
    },

    'post.php' => function() {
        include __DIR__ . '/post.php';
    }
];


if (isset($routes[$path])) {
    $routes[$path]();
} else {
    http_response_code(404);
    $smarty->display('404.tpl');
}
