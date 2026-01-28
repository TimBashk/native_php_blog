<?php

namespace App\Controllers;

use App\Http\Request;
use App\Models\Category;
use App\Models\Post;
use Smarty\Smarty;

class PostController
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
        $postId = $this->request->getInt('id', 0, true);

        $post = Post::find($postId);
        if (!$post) {
            http_response_code(404);
            $this->smarty->display('404.tpl');
            exit(0);
        }

        $categories = Category::getByPost($postId);

        Post::incrementViews($postId);

        $relatedPosts = Post::getRelated($postId, 3);

        $breadcrumbs = [
            ['title' => 'Главная', 'url' => '/index.php'],
        ];

        if ($categories) {
            $breadcrumbs[] = [
                'title' => $categories[0]['name'],
                'url' => '/category.php?id=' . $categories[0]['id']
            ];
        }

        $breadcrumbs[] = [
            'title' => $post['title'],
            'url' => ''
        ];

        // Передаем данные в Smarty
        $this->smarty->assign('post', $post);
        $this->smarty->assign('relatedPosts', $relatedPosts);
        $this->smarty->assign('breadcrumbs', $breadcrumbs);

        $this->smarty->display('post.tpl');
    }
}