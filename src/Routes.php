<?php declare(strict_types = 1);

return [
    ['GET', '/', ['AdsJob\Controllers\FrontendController', 'index']], //renders views/index.php
    ['GET', '/post-find-job', ['AdsJob\Controllers\FrontendController', 'showPostFindJob']], //renders views/post-find-job.php
    ['GET', '/login', ['AdsJob\Controllers\FrontendController', 'showLogin']], //renders views/login.php
    ['GET', '/register', ['AdsJob\Controllers\FrontendController', 'showRegister']], //renders views/register.php
    ['GET', '/{slug}' , ['AdsJob\Controllers\PageController','show']],
];

