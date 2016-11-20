<?php

namespace App\Http\Controllers;

use App\User;
use App\Text;
use App\Link;
use App\Tba;
use App\Image;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class FormController extends Controller
{

    public function store(Request $request)
    {

        $user_id  =  Auth::user()->id;

        session_start();
        session_regenerate_id();
        if(!isset($_SESSION["timeout"])) {
            $_SESSION["timeout"] = time();
        }
        if ((time() - $_SESSION["timeout"]) > (20 * 60)) {
            Auth::logout(User::find($user_id));
            unset($_SESSION["timer"]);
        }
        $_SESSION["timeout"] = time();



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

        if(isset($_POST['delete'])) {
            $getImageToDelete = Input::get('delete');

            $imagesArray = DB::table('images')->where('user_id', $user_id)->get();
            $images = array();
            if(!$imagesArray->isEmpty()) {
                $imagesArray = DB::table('images')->where('user_id', $user_id)->first()->image;
                $images = explode(',' , $imagesArray);
            }
            foreach($getImageToDelete as $key => $imgValue){
                $images[$imgValue] = null;
            }
            $newImages = array();
            foreach($images as $value) {
                if ($value != null) {
                    array_push($newImages,$value);
                }
            }
            $allNewImages = implode(',' , array_filter($newImages));
            if($newImages == null){
                DB::table('images')->where('user_id', '=', $user_id)->delete();
            } else {
                DB::table('images')->update(
                    ['user_id' => $user_id, 'image' => $allNewImages, 'created_at' => new \DateTime(), 'updated_at' => new \DateTime()]);
            }
        }

        $getLinks = Input::get('website');
        $allLinks = implode(',', array_filter($getLinks));
        $check = Link::where('user_id', $user_id)->get();

        if($check->isEmpty()) {
            DB::table('links')->insert(
                ['user_id' => $user_id, 'linkBody' => "", 'created_at' => new \DateTime(), 'updated_at' => new \DateTime()]);
        }

        $links = Link::where('user_id', $user_id)->first();
        $links->linkBody = $allLinks;
        $links->save();


        if(Input::hasFile('myImage')) {

            $file = Input::file('myImage');
            $fileType = $file->getClientOriginalName();

            $imagesArray = DB::table('images')->where('user_id', $user_id)->get();
            $image = array();
            if (!$imagesArray->isEmpty()) {
                $imagesArray = DB::table('images')->where('user_id', $user_id)->first()->image;
                $image = explode(',', $imagesArray);
            }

            if (sizeof($image) == 4) {
                $errorMsg = "Maximum of 4 images per account";
                return view('wrongType', compact('errorMsg'));
            } else if(preg_match('/\.(jpg|jpeg|gif)(?:[\?\#].*)?$/i', $fileType) != 1){
                $errorMsg = "Wrong file type";
                return view('wrongType', compact('errorMsg'));
            } else {
                $destinationPath = public_path(). '/uploads/';
                $filename = $file->getClientOriginalName();

                $allImageNames = Input::get('imageNames');
                if($allImageNames != null) {
                    $allImages = implode(',', array_filter($allImageNames));
                    $allImages = $allImages . ',' . $filename;
                } else {
                    $allImages = $filename;
                }

                $check = Image::where('user_id', $user_id)->get();
                if($check->isEmpty()) {
                    DB::table('images')->insert(
                        ['user_id' => $user_id, 'image' => "", 'created_at' => new \DateTime(), 'updated_at' => new \DateTime()]);
                }

                $images = Image::where('user_id', $user_id)->first();
                $images->image = $allImages;
                $images->save();

                $file->move($destinationPath, $filename);
            }
        }
        return back();
    }

}