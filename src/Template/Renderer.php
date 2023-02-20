<?php declare(strict_types = 1);

namespace AdsJob\Template;

interface Renderer{

    public function render($template, $data = []) : string;
}