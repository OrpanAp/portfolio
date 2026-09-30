<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

class View
{
    public function __construct(
        private string $viewsPath
    ) {}

    public function render(
        string $view,
        array $data = [],
        ?string $layout = 'layouts.app'
    ): string {
        $content = $this->renderFile(
            $view,
            $data
        );

        if ($layout === null) {
            return $content;
        }

        return $this->renderFile(
            $layout,
            [
                ...$data,
                'content' => $content,
            ]
        );
    }

    private function renderFile(
        string $view,
        array $data = []
    ): string {
        $viewFile = $this->viewsPath
            . '/'
            . str_replace('.', '/', $view)
            . '.php';

        if (!is_file($viewFile)) {
            throw new RuntimeException(
                "View not found: {$viewFile}"
            );
        }

        extract($data, EXTR_SKIP);

        ob_start();

        require $viewFile;

        return (string) ob_get_clean();
    }
}
