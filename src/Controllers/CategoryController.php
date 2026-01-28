<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\Category;
use App\Models\Post;
use Smarty\Smarty;

class CategoryController
{
    private Smarty $smarty;
    private Request $request;

    public function __construct(Smarty $smarty, Request $request)
    {
        $this->smarty = $smarty;
        $this->request = $request;
    }

    public function show(): void
    {
        $categoryId = $this->request->getInt('id', 0, true);
        $sort = $this->request->getString('sort', 'date');

        $category = Category::find($categoryId);
        if (!$category) {
            http_response_code(404);
            $this->smarty->display('404.tpl');
            exit(0);
        }

        $breadcrumbs = [
            ['title' => 'Главная', 'url' => '/index.php'],
            ['title' => $category['name'], 'url' => '']
        ];

        $page = max(1, $this->request->getInt('page', 1));
        $perPage = 3;
        $offset = ($page - 1) * $perPage;

        $posts = Post::getByCategoryWithPagination($categoryId, $sort, $perPage, $offset);
        $totalPosts = Post::countByCategory($categoryId);
        $totalPages = (int)ceil($totalPosts / $perPage);

        $this->smarty->assign('category', $category);
        $this->smarty->assign('posts', $posts);
        $this->smarty->assign('currentSort', $sort);
        $this->smarty->assign('currentPage', $page);
        $this->smarty->assign('totalPages', $totalPages);
        $this->smarty->assign('breadcrumbs', $breadcrumbs);

        $this->smarty->display('category.tpl');
    }
}