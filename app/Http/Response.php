<?php

namespace App\Http;

class Response
{
    protected $view;
    protected $json;

    public function __construct($params)
    {
        $this->view = $params['view'] ?? null;
        $this->json = $params['json'] ?? null;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getJson()
    {
        return json_encode($this->json);
    }

    public function sendView()
    {
        $view = $this->getView();
        $content = file_get_contents(viewPath($view));
        require viewPath('layout');
    }

    public function sendJson()
    {
        header('Content-Type: application/json');
        echo $this->getJson();
    }

    public function send()
    {
        if (isset($this->view)) {
            $this->sendView();
        } else if (isset($this->json)) {
            $this->sendJson();
        }
    }
}
