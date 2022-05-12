<?php

namespace App\Http\Router;

class Route
{
    private static $uris = array();

    public static function add($method, $uri, $function = null)
    {
        Route::$uris[] = new Uri($uri, $method, $function);
    }

    public static function get($uri, $function = null)
    {
        return Route::add('GET', $uri, $function);
    }

    public static function post($uri, $function = null)
    {
        return Route::add('POST', $uri, $function);
    }

    public static function put($uri, $function = null)
    {
        return Route::add('PUT', $uri, $function);
    }

    public static function delete($uri, $function = null)
    {
        return Route::add('DELETE', $uri, $function);
    }

    public static function any($uri, $function = null)
    {
        return Route::add('ANY', $uri, $function);
    }

    private static function requestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    private static function requestUri()
    {
        return str_replace('/userApp', '', $_SERVER['REQUEST_URI']);
    }

    public static function submit()
    {
        $method = static::requestMethod();
        $requestUri = static::requestUri();
        foreach (static::$uris as $uri) {
            if ($uri->match($requestUri)) {
                return $uri->call();
            }
        }
        header("Content-Type: text/html");
        echo 'La uri (<a href="' . $requestUri . '">' . $requestUri . '</a>) no se encuentra registrada con el método ' . $method . '.';
        echo '<br><hr>Uris Disponibles:<br>';
        print_r(static::$uris);
    }
}
