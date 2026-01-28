<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Models\Category;
use Smarty\Smarty;

$smarty = new Smarty();
$smarty->setTemplateDir(__DIR__ . '/../templates');
$smarty->setCompileDir(__DIR__ . '/../templates_c');

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$path = trim($path, '/');

if ($path === '' || $path === 'index.php') {
    $categories = Category::getWithPosts(3);
    $smarty->assign('categories', $categories);
    $smarty->display('index.tpl');

} elseif (str_starts_with($path, 'category.php')) {
    include __DIR__ . '/category.php';

} elseif (str_starts_with($path, 'post.php')) {
    include __DIR__ . '/post.php';
} else {
    http_response_code(404);
    $smarty->display('404.tpl');
}
