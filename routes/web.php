<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});
Route::post('/', function () {
    return view('wrongType');
});

Route::get('/test', function () {
    return view('test');
});
Route::get('/home', 'HomeController@index');
Route::post('/home', 'FormController@store');

Route::get('/unlock', 'UnlockController@index');
Route::post('/unlock', 'UnlockController@unlock');

Route::get('/activate', 'ActivateController@index');

Route::get('/send-mail', function() {
    require_once '/home/vagrant/Code/note-to-myself/vendor/swiftmailer/swiftmailer/lib/swift_init.php';
    require_once "/home/vagrant/Code/note-to-myself/vendor/swiftmailer/swiftmailer/lib/swift_required.php";

    $subject = 'My first email with SwiftMailer';
    $from = array('rshellborndev@gmail.com' =>'Rachel Shellborn');
    $to = array('rachel@shellborn.com');

    $text = "This actually works, and I actually hate pathing issues. This is for plain text.";
    $html = "<em>This actually works, and it's super cool!</em>";

    $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl");
    $transport->setUsername('rshellborndev@gmail.com');
    $transport->setPassword('superCool1');
    $swift = Swift_Mailer::newInstance($transport);

    $message = new Swift_Message($subject);
    $message->setFrom($from);
    $message->setBody($html, 'text/html');
    $message->setTo($to);
    $message->addPart($text, 'text/plain');

    if ($recipients = $swift->send($message, $failures))
    {
        echo 'Message successfully sent!';
    } else {
        echo "There was an error:\n";
        print_r($failures);
    }

});

Auth::routes();



Route::any('/captcha-test', function()
{
    if (Request::getMethod() == 'POST')
    {
        $rules = ['captcha' => 'required|captcha'];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails())
        {
            echo '<p style="color: #ff0000;">Incorrect!</p>';
        }
        else
        {
            echo '<p style="color: #00ff30;">Matched :)</p>';
        }
    }

    $form = '<form method="post" action="captcha-test">';
    $form .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    $form .= '<p>' . captcha_img() . '</p>';
    $form .= '<p><input type="text" name="captcha"></p>';
    $form .= '<p><button type="submit" name="check">Check</button></p>';
    $form .= '</form>';
    return $form;
});
