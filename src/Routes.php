<?php declare(strict_types = 1);

return [
    ['GET', '/', [AdsJob\Controllers\FrontendController::class, 'index']],
    ['GET', '/login', [AdsJob\Controllers\FrontendController::class, 'login']],
    ['GET', '/register', [AdsJob\Controllers\FrontendController::class, 'register']],
    ['GET', '/p/create', [AdsJob\Controllers\JobController::class, 'create']],
    ['GET', '/p/{job_id}', [AdsJob\Controllers\JobController::class, 'show']],
    ['GET', '/p/{job_id}/edit', [AdsJob\Controllers\JobController::class, 'edit']],
    ['POST', '/p/store', [AdsJob\Controllers\JobController::class, 'store']],
    ['POST', '/review/{job_id}/store', [AdsJob\Controllers\ReviewController::class, 'store']],
    ['POST', '/user/store', [AdsJob\Controllers\UserController::class, 'store']],
    ['GET', '/user/profile', [AdsJob\Controllers\UserController::class, 'profile']],
    ['GET', '/user/profile/edit', [AdsJob\Controllers\UserController::class, 'editProfile']],
    ['GET', '/user/jobs', [AdsJob\Controllers\UserController::class, 'myJobs']],
    ['POST', '/user/update', [AdsJob\Controllers\UserController::class, 'update']],
    ['POST', '/user/image/store', [AdsJob\Controllers\UserController::class, 'storeImage']],
    ['POST', '/user/delete', [AdsJob\Controllers\UserController::class, 'delete']],
    ['POST', '/user/login', [AdsJob\Controllers\Auth\LoginController::class, 'login']],
    ['POST', '/logout', [AdsJob\Controllers\Auth\LoginController::class, 'logout']],
    ['GET', '/search/results', [AdsJob\Controllers\SearchController::class, 'show']],
    ['GET', '/chats', [AdsJob\Controllers\ChatRoomController::class, 'index']],
    ['GET', '/chat/{chat_id}/{job_id}', [AdsJob\Controllers\ChatRoomController::class, 'show']],
    ['POST', '/chat/{chat_id}/{job_id}', [AdsJob\Controllers\MessageController::class, 'store']],
];

