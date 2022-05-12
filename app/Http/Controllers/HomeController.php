<?php

namespace App\Http\Controllers;

use \App\Http\Request;

class HomeController
{
    public function index(Request $request)
    {
        return view('home');
    }

    public function indexJson(Request $request, $other)
    {
        return json([
            'request' => $request,
            'params' => $other,
        ]);
    }
}
