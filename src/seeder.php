<?php

require __DIR__ . '/../src/bootstrap.php';

use App\DataProviders\BlogSeeder;

$seeder = new BlogSeeder();
$seeder->run();