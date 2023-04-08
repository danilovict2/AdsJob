<?php declare(strict_types = 1);

return [
    ['GET', '/', [AdsJob\Controllers\FrontendController::class, 'index']],
    ['GET', '/login', [AdsJob\Controllers\FrontendController::class, 'login']],
    ['GET', '/logout', [AdsJob\Controllers\FrontendController::class, 'logout']],
    ['GET', '/register', [AdsJob\Controllers\FrontendController::class, 'register']],
    ['GET', '/messages/{user_id}/', [AdsJob\Controllers\FrontendController::class, 'messages']],
    ['GET', '/profile/{user_id}', [AdsJob\Controllers\ProfileController::class, 'index']],
    ['GET', '/profile/{user_id}/reviews', [AdsJob\Controllers\ProfileController::class, 'reviews']],
    ['GET', '/profile/{user_id}/edit', [AdsJob\Controllers\ProfileController::class, 'edit']],
    ['GET', '/p/create', [AdsJob\Controllers\JobController::class, 'create']],
    ['GET', '/p/{job_id}', [AdsJob\Controllers\JobController::class, 'show']],
    ['GET', '/review/create', [AdsJob\Controllers\ReviewController::class, 'create']],
    ['POST', '/p/store', [AdsJob\Controllers\JobController::class, 'store']],
    ['POST', '/user/store', [AdsJob\Controllers\UserController::class, 'store']],
    ['POST', '/user/login', [AdsJob\Controllers\Auth\LoginController::class, 'login']],
    ['POST', '/user/logout', [AdsJob\Controllers\Auth\LoginController::class, 'logout']],
    ['POST', '/review/store', [AdsJob\Controllers\ReviewController::class, 'store']],
];

