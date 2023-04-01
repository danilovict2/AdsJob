<?php

declare(strict_types=1);

$injector = new \Auryn\Injector;

$injector->alias('Http\Request', 'Http\HttpRequest');
$injector->share('Http\HttpRequest');
$injector->define('Http\HttpRequest', [
    ':get' => $_GET,
    ':post' => $_POST,
    ':cookies' => $_COOKIE,
    ':files' => $_FILES,
    ':server' => $_SERVER,
]);

$injector->alias('Http\Response', 'Http\HttpResponse');
$injector->share('Http\HttpResponse');

$injector->alias('AdsJob\Template\Renderer', 'AdsJob\Template\TwigRenderer');
$injector->define('\Twig\Environment', [
        ':loader' => new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/views')
    ]
);

$injector->alias('AdsJob\Template\FrontendRenderer', 'AdsJob\Template\FrontendTwigRenderer');

return $injector;
