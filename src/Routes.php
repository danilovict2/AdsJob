<?php declare(strict_types = 1);

return [
    ['GET', '/', ['AdsJob\Controllers\Homepage', 'show']],
    ['GET', '/{slug}' , ['AdsJob\Controllers\Page','show']],
];

