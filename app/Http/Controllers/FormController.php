<?php

namespace App\Http\Controllers;

use App\User;
use App\Text;
use App\Link;
use App\Tba;
use App\Image;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class FormController extends Controller
{

    public function index(){
        $user_id  =  Auth::user()->id;
        if(is_null($user_id))
        {
            return redirect("/welcome");
        }
        $texts    = Text::where('user_id', $user_id)->first();//->textBody;
        $tbds     = Tba::where('user_id', $user_id)->first();//->tbaBody;
        $links    = Link::where('user_id', $user_id)->first();//->linkBody;
        $images    = Image::where('user_id',$user_id)->first();//->image;

        return view('home', compact('texts', 'tbds' , 'links' ,'images'));

    }

    public function store(Request $request, User $user)
    {
        if ($request->has('image'))
        {
            $image = new Image;
            $image->image = $request->image;
            $user->images()->save($image);
        }
        if ($request->has('linksBody'))
        {
            $link = new Link();
            $link->linksBody = $request->linksBody;
            $user->links()->save($link);
        }
        if ($request->has('tbaBody'))
        {
            $tba = new Tba();
            $tba->tbaBody = $request->tbaBody;
            $user->tbas()->save($tba);
        }
        if ($request->has('textBody'))
        {
            $text = new Text();
            $text->textBody = $request->textBody;
            $user->texts()->save($text);
        }

        return back();
    }
}
