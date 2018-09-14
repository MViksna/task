<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function debt()
    {
        return view('debt');
    }

    public function saveDebt()
    {
        return array(
            'success' => true,
            'data' => 'nothing',
        );
    }

    public function payment()
    {
        return view('payment');
    }

    public function reports()
    {
        return view('reports');
    }
}
