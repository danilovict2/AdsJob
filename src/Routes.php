<?php declare(strict_types = 1);

return [
    ['GET', '/', ['AdsJob\Controllers\FrontendController', 'index']],
    ['GET', '/post-find-job', ['AdsJob\Controllers\FrontendController', 'postFindJob']],
    ['GET', '/login', ['AdsJob\Controllers\FrontendController', 'login']],
    ['GET', '/register', ['AdsJob\Controllers\FrontendController', 'register']],
    ['GET', '/profile/{user_id}', ['AdsJob\Controllers\ProfileController', 'index']],
    ['GET', '/profile/{user_id}/comments', ['AdsJob\Controllers\ProfileController', 'comments']],
    ['GET', '/profile/{user_id}/reviews', ['AdsJob\Controllers\ProfileController', 'reviews']],
    ['GET', '/profile/{user_id}/edit', ['AdsJob\Controllers\ProfileController', 'edit']],
    ['GET', '/p/create', ['AdsJob\Controllers\JobController', 'create']],
    ['GET', '/p/{job_id}', ['AdsJob\Controllers\JobController', 'show']],
    ['POST', '/user/store', ['AdsJob\Controllers\UserController', 'store']],
];

