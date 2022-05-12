<?php

if (!function_exists('view')) {
    function view($view)
    {
        return new \App\Http\Response(array('view' => $view));
    }
}

if (!function_exists('viewPath')) {
    function viewPath($view)
    {
        return __DIR__ . "/../../views/$view.php";
    }
}

if (!function_exists('json')) {
    function json($json)
    {
        return new \App\Http\Response(array('json' => $json));
    }
}
