<?php

namespace App\Http\Controllers;

use App\Box;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home',[
            'boxes' => Box::all()
        ]);
    }
}
