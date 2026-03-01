<?php

namespace App\Core;

class View
{
    private string $viewPath;
    private array $data = [];

    public function __construct(string $view, array $data = [])
    {
        $this->viewPath = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
        $this->data = $data;
    }

    public function render(): void
    {
        if (!file_exists($this->viewPath)) {
            die("View not found: {$this->viewPath}");
        }

        extract($this->data);
        require $this->viewPath;
    }

    public static function make(string $view, array $data = []): View
    {
        return new self($view, $data);
    }

    public function with(string $key, mixed $value): View
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function getPath(): string
    {
        return $this->viewPath;
    }
}
