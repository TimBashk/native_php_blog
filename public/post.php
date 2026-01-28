<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Http\Request;
use App\Controllers\PostController;
use Smarty\Smarty;

$smarty = new Smarty();
$smarty->setTemplateDir(__DIR__ . '/../templates');
$smarty->setCompileDir(__DIR__ . '/../templates_c');

$request = new Request();

$controller = new PostController($smarty, $request);
$controller->show();


