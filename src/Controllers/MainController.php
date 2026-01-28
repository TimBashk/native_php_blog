<?php

namespace App\Controllers;

use App\Models\Category;
use Smarty\Smarty;

class MainController
{
    private Smarty $smarty;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
    }

    public function index(): void
    {
        // Получаем категории с последними 3 постами
        $categories = Category::getWithPosts(3);

        // Передаем в Smarty
        $this->smarty->assign('categories', $categories);

        // Отображаем шаблон главной страницы
        $this->smarty->display('index.tpl');
    }
}