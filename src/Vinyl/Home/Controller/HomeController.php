<?php

namespace Vinyl\Home\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Vinyl\Debug\Util\Debug;
use tmhOAuth;
use Doctrine\DBAL\Schema\Table;
use Silex\Application\SecurityTrait;

class HomeController implements ControllerProviderInterface
{
    protected $_oAuth;

    public function init(Request $request, Application $app) 
    {
        // set up oauth
        $this->_oAuth = new tmhOAuth(array(
              'consumer_key' => $app['api.discogs']['consumer_key'],
              'consumer_secret' => $app['api.discogs']['consumer_secret'],
              'timestamp' => true,
              'host' => 'api.discogs.com',
              'nonce' => true
        ));

        $this->callback = "http://{$_SERVER['SERVER_NAME']}/";
    }

    public function connect(Application $app)
    {
        // Debug::dump($this->app);
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        // init
        $controllers->get('/', array($this, 'index'))->before(array($this, 'init'));

        // routing 
        $controllers->get('/', array($this, 'index'))->bind('homepage');
        $controllers->get('/login', array($this, 'login'))->bind('login');
        $controllers->get('/discogs/{name}', array($this, 'discogs'))->bind('discogs');

        return $controllers;
    }

    public function index(Application $app)
    {
        $token = $app['security']->getToken();
        $user = $token->getUser();
        // Debug::dump($user);
        // if ($app['security']->isGranted('ROLE_ADMIN')) {
        //     echo 'jeet';
        // }

        // $test = $app['security']->isGranted('ROLE_ADMIN');
        // Debug::dump($test);
        // $schema = $app['db']->getSchemaManager();
        // $this->_oAuth->serviceName = 'Discogs';
        // $code = $this->_oAuth->request('GET', $this->_oAuth->url('oauth/request_token', ''), array(
        //     'oauth_callback' => $this->callback . '/connect'
        // ));
        // die();

        // Debug::dump($this->_oAuth->extract_params($this->_oAuth->response['response']));
        // Debug::dump($this->_oAuth->url);
        // Debug::dump($code);


        // Debug::dump($app['security.firewalls']);


        // $service = new \Discogs\Service();
        // $result = $service->search(array('q' => 'test'));
        // $result =  $result->getIterator();

        // Debug::dump($result);
        return $app['twig']->render(
            'Home/Views/index.twig'
        );
    }

    public function discogs(Application $app, $name) {
        Debug::dump($name);
        return $app['twig']->render(
            'Home/Views/index.twig'
        );
        // $service = new \Discogs\Service();
        // $label = $service->getArtist('DaftPunk');
        // va::dump($app);
        // var_dump($app->redirect('/'));
        // return $service;
    }

    public function login(Application $app, Request $request) {
        return $app['twig']->render('Home/Views/login.twig', array(
            'error'         => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    }
}
