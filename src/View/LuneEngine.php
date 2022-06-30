<?php

namespace Lune\View;

class LuneEngine implements View {
    protected string $viewsDirectory;

    protected string $defaultLayout = "main";

    protected string $contentAnnotation = "@content";

    public function __construct(string $viewsDirectory) {
        $this->viewsDirectory = $viewsDirectory;
    }

    public function render(string $view, array $params = [], string $layout = null): string {
        $layoutContent = $this->renderLayout($layout ?? $this->defaultLayout);
        $viewContent = $this->renderView($view, $params);

        return str_replace($this->contentAnnotation, $viewContent, $layoutContent);
    }

    protected function renderView(string $view, array $params = []): string {
        return $this->phpFileOutput("{$this->viewsDirectory}/{$view}.php", $params);
    }

    protected function renderLayout(string $layout): string {
        return $this->phpFileOutput("{$this->viewsDirectory}/layouts/{$layout}.php");
    }

    protected function phpFileOutput(string $phpFile, array $params = []): string {
        foreach ($params as $param => $value) {
            $$param = $value;
        }

        ob_start();

        include_once $phpFile;

        return ob_get_clean();
    }
}
