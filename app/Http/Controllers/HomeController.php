<?php

namespace App\Http\Controllers;
use App\Text;
use App\Link;
use App\Tba;
use App\Image;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

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

        $user_id =  Auth::user()->id;

        $texts = DB::table('texts')->where('user_id', $user_id)->get();
        $tbas = DB::table('tbas')->where('user_id', $user_id)->get();
        $linksString = DB::table('links')->where('user_id', $user_id)->first()->linksBody;

        $links = explode(',', $linksString);

        return view('home', compact('texts','tbas','links'));

        $user_id =  \Auth::user()->id;

        session_start();
        if(!isset($_SESSION["timer"])){
            $_SESSION["timer"] = time();
        }

        if ((time() - $_SESSION["timer"]) > (.1 * 60)) {
            $user = \User::find($user_id);
            Auth::logout($user);
            unset($_SESSION["timer"]);
            return view('auth.login')->with('timeout', "you been logout for inactivity");
        }

        $_SESSION["timer"] = time();
        return view('home');

    }
}
