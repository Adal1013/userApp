<?php

namespace App\Http\Router;

class Uri
{
    protected $uri;
    protected $httpMethod;
    protected $function;
    protected $matches;

    public function __construct($uri, $httpMethod, $function)
    {
        $this->uri = $uri;
        $this->httpMethod = $httpMethod;
        $this->function = $function;
    }

    public function formatController($controller)
    {
        $controller = ucfirst($controller);
        return "App\Http\Controllers\\$controller";
    }

    public function getRequest()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        $_REQUEST = array_merge($_REQUEST, (array) $data);
        return new \App\Http\Request($_REQUEST);
    }

    public function match($requestUri)
    {
        $requestUriParts = explode("/", $requestUri);
        if ($requestUriParts[0] == '') {
            array_shift($requestUriParts);
        }
        $uriParts = explode("/", $this->uri);
        if ($uriParts[0] == '') {
            array_shift($uriParts);
        }
        if (count($uriParts) != count($requestUriParts)) {
            return false;
        } else if ($this->httpMethod != $_SERVER['REQUEST_METHOD'] && $this->httpMethod != "ANY") {
            return false;
        } else {
            foreach ($uriParts as $key => $uriPart) {
                if ((strpos($uriPart, '{') === false && strpos($uriPart, '}') === false) && $uriPart != $requestUriParts[$key]) {
                    return false;
                } else if (strpos($uriPart, '{') !== false && strpos($uriPart, '}') !== false) {
                    $uriKey = str_replace('{', '', $uriPart);
                    $uriKey = str_replace('}', '', $uriKey);
                    $this->matches[$uriKey] = $requestUriParts[$key];
                }
            }
        }
        return true;
    }

    public function call()
    {
        try {
            if (is_string($this->function)) {
                $this->execFunctionFromController();
            } else {
                $this->execFunction();
            }
        } catch (\Exception $e) {
            echo 'Ha ocurrido un error: ' . $e->getMessage();
        }
    }


    private function execFunction()
    {
        try {
            $this->response = call_user_func_array(
                $this->function,
                $this->matches
            );
            if ($this->response instanceof \App\Http\Response) {
                $this->response->send();
            } else {
                return json($this->response);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function execFunctionFromController()
    {
        $launch = $this->getControllerAndMethod();
        $this->response = call_user_func(
            [
                new $launch[0],
                $launch[1]
            ],
            $this->getRequest(),
            $this->matches
        );
        try {
            if ($this->response instanceof \App\Http\Response) {
                $this->response->send();
            } else {
                throw new \Exception("El método $launch[0].$launch[1] no existe", -1);
            }
        } catch (\Exception $e) {
            return json_encode($e->getMessage());
        }
    }

    private function getControllerAndMethod()
    {
        $controllerAndMethodArray = array();
        if (strpos($this->function, '@')) {
            $controllerAndMethod = explode('@', $this->function);
            $controllerAndMethodArray[0] = $this->formatController($controllerAndMethod[0]);
            $controllerAndMethodArray[1] = $controllerAndMethod[1];
        } else {
            $controllerAndMethodArray[0] = $this->formatController($this->function);
            $controllerAndMethodArray[1] = ($this->uri == '/') ? 'index' : $this->formatToCamelCase($this->uri);
        }
        return $controllerAndMethodArray;
    }

    private function formatToCamelCase($string)
    {
        $str = str_replace(' ', '', $string);
        $str = str_replace('_', '', $string);
        $str = str_replace('-', '', $string);
        $str = ucwords($string);
        $str[0] = strtolower($str[0]);
        return $str;
    }
}
