<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;

class ActivateController extends Controller
{
    public function index(Request $request) {
        $email = $request->input('email');

        $user = User::where('email', $email)->get()->first();

        $user->active = 'yes';
        $user->save();


        return view('activate');
    }
}
