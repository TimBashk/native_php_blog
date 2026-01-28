<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Http\Request;
use App\Controllers\CategoryController;
use Smarty\Smarty;

$smarty = new Smarty();
$smarty->setTemplateDir(__DIR__ . '/../templates');
$smarty->setCompileDir(__DIR__ . '/../templates_c');

$request = new Request();

$controller = new CategoryController($smarty, $request);
$controller->show();