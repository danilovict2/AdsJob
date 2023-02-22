<?php declare(strict_types = 1);

namespace AdsJob\Page;

interface PageReader{
    public function readBySlug(string $slug) : string;
}