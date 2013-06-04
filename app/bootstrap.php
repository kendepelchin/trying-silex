<?php

// Composer autoloading (PSR-0).
require_once __DIR__ . '/../vendor/autoload.php';

// Require config.
require_once __DIR__ . '/config.php';

// Namespaces.
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\FormServiceProvider;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

use Vinyl\User\Provider\UserProvider;


/**
 * Application Configuration.
 *
 */
$app = new Silex\Application();
$app['debug'] = true;

/**
 * For configs!
 *
 */
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__ . '/config.php'));

/**
 * MONOLOG - For logging.
 *
 */
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . 'logs/' . date('Y:m:d') . 'development.log',
));

/**
 * Twig - Template Engine.
 *
 */
$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../src/Vinyl'
));

// shorthands
$app->register(new UrlGeneratorServiceProvider());

/**
 * Doctrine - Database Access.
 *
 */
$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbname' => 'vinyl',
        'host' => 'localhost',
        'user' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
    ),
));

/**
 * Session
 *
 */
$app->register(new SessionServiceProvider());

$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' =>  array(
        'admin' => array(
            'anonymous'=>array(),
            'pattern' => '^/',
            'http' => true,
            'form' => array('login_path' => '/user/login', 'check_path' => '/admin/login_check'),
            'logout' => array('logout_path' => '/user/logout'),
            'users' => $app->share(function() use ($app){
                return new UserProvider($app['db']);
            })
        ),
    ),
));

// Use Repository Service Provider — @note: Be sure to install RSP via Composer first!
// https://github.com/KnpLabs/RepositoryServiceProvider
$app->register(new Knp\Provider\RepositoryServiceProvider(), array(
        'repository.repositories' => array(
            'users' => 'Vinyl\\User\\Repository\\Model',
    )
));

$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallback' => 'en',
));
