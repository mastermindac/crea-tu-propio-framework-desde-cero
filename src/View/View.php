<?php

namespace Lune\View;

interface View {
    public function render(string $view): string;
}
