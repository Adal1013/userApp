<?php

namespace App\Http;

class Request
{
    protected $request;
    protected $data;

    public function __construct($request, $flag = true)
    {
        $this->request = $request;
        $this->extractData();
    }

    public function extractData()
    {
        $this->data = array();
        foreach ($this->request as $key => $value) {
            if (is_object($value) || is_array($value)) {
                $this->data[$key] = new Request($value, false);
            } else if ($key != "http_referer") {
                $this->data[$key] = $value;
            }
        }
    }

    public function __get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function all()
    {
        return $this->data;
    }

    public function getAllHeaders()
    {
        return getallheaders();
    }

    public function getRequestUri()
    {
        return $_SERVER["REQUEST_URI"];
    }

    public function getMethod()
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    public function getAllServerData()
    {
        return $_SERVER;
    }

    public function send()
    {
        Router\Route::submit();
    }
}
