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
    public function store(Request $request)
    {
        $user_id  =  Auth::user()->id;

        $getNotes = Input::get('texts');
        $notes = Text::where('user_id', $user_id)->first();

        if ($notes == NULL){
            DB::table('texts')->insert(
                ['user_id' => $user_id, 'textBody' => $getNotes, 'created_at'=>new \DateTime(),'updated_at'=>new \DateTime()]);
        } else {
            $notes->textBody = $getNotes;
            $notes->save();
        }

        $getTbas = Input::get('tbas');
        $tbas = Tba::where('user_id', $user_id)->first();

        if ($tbas == NULL){
            DB::table('tbas')->insert(
                ['user_id' => $user_id, 'tbaBody' => $getTbas, 'created_at'=>new \DateTime(),'updated_at'=>new \DateTime()]);
        } else {
            $tbas->tbaBody = $getTbas;
            $tbas->save();
        }

        $getLinks = Input::get('website');
        $allLinks = implode(',', array_filter($getLinks));

        $links = Link::where('user_id', $user_id)->first();

        $links->linksBody = $allLinks;
        $links->save();

        return back();
    }
}












