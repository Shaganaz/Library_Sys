<?php

namespace Shaganaz\Libsys\Core;

class View
{
    public static function render($view, $data = [])
    {
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';

        if (file_exists($viewPath)) {
            extract($data);
            include $viewPath; 
        } else {
            echo "View '$view' not found!";
        }
    }
}
