<?php declare(strict_types = 1);

return [
    ['GET', '/', [AdsJob\Controllers\FrontendController::class, 'index']],
    ['GET', '/post-find-job', [AdsJob\Controllers\FrontendController::class, 'postFindJob']],
    ['GET', '/login', [AdsJob\Controllers\FrontendController::class, 'login']],
    ['GET', '/register', [AdsJob\Controllers\FrontendController::class, 'register']],
    ['GET', '/profile/{user_id}', [AdsJob\Controllers\ProfileController::class, 'index']],
    ['GET', '/profile/{user_id}/comments', [AdsJob\Controllers\ProfileController::class, 'comments']],
    ['GET', '/profile/{user_id}/reviews', [AdsJob\Controllers\ProfileController::class, 'reviews']],
    ['GET', '/profile/{user_id}/edit', [AdsJob\Controllers\ProfileController::class, 'edit']],
    ['GET', '/p/create', [AdsJob\Controllers\JobController::class, 'create']],
    ['GET', '/p/{job_id}', [AdsJob\Controllers\JobController::class, 'show']],
    ['POST', '/user/store', [AdsJob\Controllers\UserController::class, 'store']],
];

