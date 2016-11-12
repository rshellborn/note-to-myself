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
    public function index(Request $request)
    {
        $user_id =  \Auth::user()->id;

        session_start();
        if(!isset($_SESSION["timer"])){
            $_SESSION["timer"] = time();
        }

        if ((time() - $_SESSION["timer"]) > (.1 * 60)) {
            //$user = User::find($user_id);
            //Auth::logout($user);
            //unset($_SESSION["timer"]);
            //return view('auth.login')->with('timeout', "you been logout for inactivity");
        }

        $_SESSION["timer"] = time();
        return view('home');
    }
}
