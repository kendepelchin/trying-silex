<?php

// Composer autoloading (PSR-0).
require_once __DIR__ . '/../vendor/autoload.php';

// Require config.
require_once __DIR__ . '/config.php';

// Namespaces.
use Knp\Provider\RepositoryServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Igorw\Silex\ConfigServiceProvider;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

// Own namespaces.
use Vinyl\User\Provider\UserProvider;


/**
 * Application Configuration
 *
 */
$app = new Silex\Application();
$app['debug'] = true;

/**
 * ConfigServiceProvider
 *
 */
$app->register(new ConfigServiceProvider(__DIR__ . '/config.php'));

/**
 * Doctrine - Database Access
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
 * Form Service Provider
 *
 */
$app->register(new FormServiceProvider());

/**
 * MONOLOG - For logging
 *
 */
$app->register(new MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../logs/' . date('Y:m:d') . 'development.log',
));

/**
 * Repository Service Provider
 * https://github.com/KnpLabs/RepositoryServiceProvider
 *
 */
$app->register(new RepositoryServiceProvider(), array(
        'repository.repositories' => array(
            'users' => 'Vinyl\\User\\Repository\\Model',
    )
));

/**
 * Security
 */
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

/**
 * Session
 *
 */
$app->register(new SessionServiceProvider());

/**
 * Translation Service Provider (needed for form & validator)
 *
 */
$app->register(new TranslationServiceProvider(), array(
    'locale_fallback' => 'en',
));

/**
 * Twig - Template Engine.
 *
 */
$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../web/public/views'
));

/**
 * Shorthands for paths & urls
 *
 */
$app->register(new UrlGeneratorServiceProvider());

/**
 * Validator Service Provider
 *
 */
$app->register(new ValidatorServiceProvider());
