<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function NotFound()
    {
        return view('errors.404');
    }
    public function InternalError()
    {
        return view('errors.500');
    }
}
