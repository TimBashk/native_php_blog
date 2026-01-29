<?php
require __DIR__ . '/../src/bootstrap.php';

use App\Controllers\MainController;
use Smarty\Smarty;

$smarty = new Smarty();
$smarty->setTemplateDir(__DIR__ . '/../templates');
$smarty->setCompileDir(__DIR__ . '/../templates_c');

$controller = new MainController($smarty);
$controller->index();
