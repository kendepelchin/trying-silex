<?php
// Bootstrapping.
require_once __DIR__ . '/../app/bootstrap.php';

// Namespaces.
use Vinyl\Home\Controller\HomeController;
use Vinyl\Admin\Controller\AdminController;
use Vinyl\User\Controller\UserController;

use Vinyl\Debug\Util\Debug;

/**
 * Routing
 * 
 */
$app->mount('/', new HomeController());
$app->mount('/admin', new AdminController());
$app->mount('/user', new UserController());

/**
 * Run
 * 
 */
$app->run();