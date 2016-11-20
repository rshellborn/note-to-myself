<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;

class UnlockController extends Controller
{
    public function index() {
        return view('unlock');
    }

    public function unlock(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');

        //actual user
        //$user = DB::table('users')->get()->where('email', $email)->first();
        $user = User::where('email', $email)->get()->first();

        //correct password from db for this user
        $actualPassword = $user->password;

        if(password_verify($password, $actualPassword)) {
            //unlock user account
            $user->status = 'unlocked';
            $user->save();

            return redirect('home');
        }

        $errors = array('password'=>"Password is incorrect, check your email for your generated password.");

        //display error message
        return view('unlock', compact('errors'));
    }
}
