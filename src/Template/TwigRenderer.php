<?php declare(strict_types = 1);

namespace AdsJob\Template;

use \Twig\Environment;

class TwigRenderer implements Renderer{
    
    private Environment $engine;

    public function __construct(Environment $engine){
        $this->engine = $engine;
    }

    public function render($template, $data = []) : string{
        return $this->engine->render($template, $data);
    }
}