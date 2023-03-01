<?php declare(strict_types = 1);

namespace AdsJob\Template;

class FrontendTwigRenderer implements FrontendRenderer{
    
    private Renderer $renderer;

    public function __construct(Renderer $renderer){
        $this->renderer = $renderer;
    }

    public function render($template, $data = []) : string{
        $data = array_merge($data, [
            'menuItems' => [['href' => '/', 'text' => 'Homepage']],
        ]);
        return $this->renderer->render($template, $data);
    }
}