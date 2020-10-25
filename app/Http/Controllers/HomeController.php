<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\RegisterController;
use Input;

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
     * Show the application dashboard with User Details.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $userData = new RegisterController();
        $userData->name = $name = Input::get('search_name');
        $userData->locality = $locality = Input::get('search_locality');
        $userDetails = $userData->getUsers();
        
        return view('home',compact('userDetails'));
    }

}
