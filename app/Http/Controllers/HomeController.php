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
        if(!isset($_SESSION["timeout"])){
            $_SESSION["timeout"] = time();
        }

        $user_id =  Auth::user()->id;

        $texts = DB::table('texts')->where('user_id', $user_id)->get();
        if(!$texts->isEmpty()) {
            $texts = DB::table('texts')->where('user_id', $user_id)->first()->textBody;
        } else {
            $texts = "";
        }

        $tbas = DB::table('tbas')->where('user_id', $user_id)->get();
        if(!$tbas->isEmpty()) {
            $tbas = DB::table('tbas')->where('user_id', $user_id)->first()->tbaBody;
        } else {
            $tbas = "";
        }

        $linksArray = DB::table('links')->where('user_id', $user_id)->get();
        $links = array();
        if(!$linksArray->isEmpty()) {
            $linksArray = DB::table('links')->where('user_id', $user_id)->first()->linkBody;
            $links = explode(',' , $linksArray);
        }

        $imagesArray = DB::table('images')->where('user_id', $user_id)->get();
        $images = array();
        if(!$imagesArray->isEmpty()) {
            $imagesArray = DB::table('images')->where('user_id', $user_id)->first()->image;
            $images = explode(',' , $imagesArray);
        }

        return view('home', compact('texts', 'tbas' , 'links', 'images'));
    }
}
//$notes = Notes::where('user_id', $user_id)->first()->mynotes;
//$tbd = Tbd::where('user_id', $user_id)->first()->mytbd;
//$linksArray =  Websites::where('user_id', $user_id)->first()->mylink;
//$email = Auth::user()->email;
//$website = explode(',' , $linksArray);
//$image = DB::table('myimages')->where('user_id',$user_id)->lists('myimage');
//Cookie::queue(Cookie::make('loginEmail', $email, 42*60));
//return view('home', compact('notes', 'tbd' , 'website' ,'image'));


//$user_id =  Auth::user()->id;
//
//$texts = DB::table('texts')->where('user_id', $user_id)->get();
//$tbas = DB::table('tbas')->where('user_id', $user_id)->get();
//
//$linksString = DB::table('links')->where('user_id', $user_id)->get();
//$links = explode(',', $linksString);
//
//
//return view('home', compact('texts','tbas','links'));
//
//// dead code
//
//$user_id =  \Auth::user()->id;
//
//session_start();
//if(!isset($_SESSION["timer"])){
//    $_SESSION["timer"] = time();
//}
//
//if ((time() - $_SESSION["timer"]) > (.1 * 60)) {
//    $user = \User::find($user_id);
//    Auth::logout($user);
//    unset($_SESSION["timer"]);
//    return view('auth.login')->with('timeout', "you been logout for inactivity");
//}
//
//$_SESSION["timer"] = time();
//return view('home');
