<?php declare(strict_types = 1);

return [
    ['GET', '/', [AdsJob\Controllers\FrontendController::class, 'index']],
    ['GET', '/login', [AdsJob\Controllers\FrontendController::class, 'login']],
    ['GET', '/register', [AdsJob\Controllers\FrontendController::class, 'register']],
    ['GET', '/messages/{user_id}', [AdsJob\Controllers\FrontendController::class, 'messages']],
    ['GET', '/p/create', [AdsJob\Controllers\JobController::class, 'create']],
    ['GET', '/p/{job_id}', [AdsJob\Controllers\JobController::class, 'show']],
    ['POST', '/p/store', [AdsJob\Controllers\JobController::class, 'store']],
    ['GET', '/review/create', [AdsJob\Controllers\ReviewController::class, 'create']],
    ['POST', '/review/store', [AdsJob\Controllers\ReviewController::class, 'store']],
    ['POST', '/user/store', [AdsJob\Controllers\UserController::class, 'store']],
    ['GET', '/user/profile', [AdsJob\Controllers\UserController::class, 'profile']],
    ['GET', '/user/profile/edit', [AdsJob\Controllers\UserController::class, 'editProfile']],
    ['GET', '/user/jobs', [AdsJob\Controllers\UserController::class, 'myJobs']],
    ['POST', '/user/update', [AdsJob\Controllers\UserController::class, 'update']],
    ['POST', '/user/image/store', [AdsJob\Controllers\UserController::class, 'storeImage']],
    ['POST', '/user/delete', [AdsJob\Controllers\UserController::class, 'delete']],
    ['POST', '/user/login', [AdsJob\Controllers\Auth\LoginController::class, 'login']],
    ['POST', '/logout', [AdsJob\Controllers\Auth\LoginController::class, 'logout']],
];

