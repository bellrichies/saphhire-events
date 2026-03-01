<?php

namespace App\Core;

class App
{
    private static ?App $instance = null;
    private Router $router;

    private function __construct()
    {
        $this->router = new Router();
    }

    public static function getInstance(): App
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function run(): void
    {
        $this->router->dispatch();
    }

    public function __clone() {}
    public function __wakeup() {}
}
