<?php

return array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'dbname' => 'vinyl',
        'host' => 'localhost',
        'user' => 'root',
        'port' => '<port>',
        'password' => 'root',
        'charset' => 'utf8',
    ),
    'api.discogs' => array(
        'debug' => array(
            'consumer_key' => 'YxVwmymcXeQEkcXxdqZv',
            'consumer_secret' => 'cIPHAdkoChFCgCRdrkCuRVYNbONZljbo'
        ),
        'app' => array(
            'consumer_key' => '',
            'consumer_secret' => '',
        ),
    ),
    'api.lastfm' => array(
        'debug' => array(
            'consumer_key' => 'c56c47c9e3a2424421590e0b19cf2c29',
            'consumer_secret' => '4cbe3e28b5621a4b1a1293f833e34090',
        ),
        'app' => array(
            'consumer_key' => '',
            'consumer_secret' => '',
        ),
        'base' => 'http://www.last.fm/api/auth/',
        'redirect_url' => 'http://silex.dev:8888/user/lastfm',
        'format' => 'json',
    ),
    'salt' => 'salt'
);
