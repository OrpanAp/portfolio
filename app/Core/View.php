<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

class View
{
    /** @var array<string, callable> */
    private array $layoutData = [];

    public function __construct(
        private string $viewsPath
    ) {}

    /**
     * Register a resolver whose returned array is merged into the data
     * of the given layout (and its components) on every render.
     * Page data with the same key wins.
     */
    public function shareWithLayout(
        string $layout,
        callable $resolver
    ): void {
        $this->layoutData[$layout] = $resolver;
    }

    public function render(
        string $view,
        array $data = [],
        ?string $layout = 'layouts.app'
    ): string {
        $shared = ($layout !== null && isset($this->layoutData[$layout]))
            ? ($this->layoutData[$layout])()
            : [];

        $content = $this->renderFile(
            $view,
            [...$shared, ...$data]
        );

        if ($layout === null) {
            return $content;
        }

        return $this->renderFile(
            $layout,
            [
                ...$shared,
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
