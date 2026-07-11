<?php

namespace App\Core;

abstract class Controller
{
    protected Request $request;

    public function __construct()
    {
        $this->request = Request::capture();
    }

    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        $GLOBALS['_layout'] = $layout;

        extract($data);

        ob_start();
        $viewPath = __DIR__ . "/../Views/{$view}.php";
        if (file_exists($viewPath)) {
            include $viewPath;
        }
        $content = ob_get_clean();

        $layoutPath = __DIR__ . "/../Views/layouts/{$layout}.php";
        if (file_exists($layoutPath)) {
            include $layoutPath;
        }
    }

    protected function json(mixed $data, int $status = 200): void
    {
        Response::json($data, $status);
    }

    protected function redirect(string $url): void
    {
        Response::redirect($url);
    }

    protected function back(): void
    {
        Response::back();
    }
}
