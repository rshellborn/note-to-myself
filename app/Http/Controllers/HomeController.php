<?php

namespace App\Http\Controllers;
use App\Text;
use App\Link;
use App\Tba;
use App\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $user_id =  Auth::user()->id;

        $texts = DB::table('texts')->where('user_id', $user_id)->get();
        $tbas = DB::table('tbas')->where('user_id', $user_id)->get();
        $links = DB::table('links')->where('user_id', $user_id)->get();


        return view('home', compact('texts','tbas','links'));
    }
}
