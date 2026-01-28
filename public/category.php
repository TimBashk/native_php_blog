<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Http\Request;
use App\Models\Category;
use App\Models\Post;
use Smarty\Smarty;

$request = new Request();
$categoryId = $request->getInt('id', 0, true);

if ($categoryId <= 0) {
    http_response_code(404);
    exit('Category not found');
}

$sort = $request->getString('sort', 'date');

$category = Category::find($categoryId);

if (!$category) {
    http_response_code(404);
    exit('Category not found');
}

$breadcrumbs = [
    ['title' => 'Главная', 'url' => '/index.php'],
    ['title' => $category['name'], 'url' => ''] // текущая страница
];

// Пагинация
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 3; // постов на страницу
$offset = ($page - 1) * $perPage;

// Получаем посты с сортировкой и лимитом
$posts = Post::getByCategoryWithPagination($categoryId, $sort, $perPage, $offset);

// Общее количество постов (для расчета страниц)
$totalPosts = Post::countByCategory($categoryId);
$totalPages = (int)ceil($totalPosts / $perPage);

// Smarty
$smarty = new Smarty();
$smarty->setTemplateDir(__DIR__ . '/../templates');
$smarty->setCompileDir(__DIR__ . '/../templates_c');

$smarty->assign('category', $category);
$smarty->assign('posts', $posts);
$smarty->assign('currentSort', $sort);
$smarty->assign('currentPage', $page);
$smarty->assign('totalPages', $totalPages);

$smarty->assign('breadcrumbs', $breadcrumbs);

$smarty->display('category.tpl');