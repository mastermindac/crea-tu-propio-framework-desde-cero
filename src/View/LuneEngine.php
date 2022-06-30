<?php

namespace Lune\View;

class LuneEngine implements View {
    protected string $viewsDirectory;

    public function __construct(string $viewsDirectory) {
        $this->viewsDirectory = $viewsDirectory;
    }

    public function render(string $view): string {
        $phpFile = "{$this->viewsDirectory}/{$view}.php";

        ob_start();

        include_once $phpFile;

        return ob_get_clean();
    }
}
