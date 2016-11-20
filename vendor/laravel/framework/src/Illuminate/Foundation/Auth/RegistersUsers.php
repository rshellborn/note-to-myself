<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $this->sendConfirmationEmail($request);
        Auth::logout(User::find($user->id));
    }

    public function sendConfirmationEmail($request) {
        require_once '/home/vagrant/Code/note-to-myself/vendor/swiftmailer/swiftmailer/lib/swift_init.php';
        require_once "/home/vagrant/Code/note-to-myself/vendor/swiftmailer/swiftmailer/lib/swift_required.php";

        $subject = 'Note To Myself - Activate Your Account';
        $from = array('rshellborndev@gmail.com' =>'Admin');
        $to = array($request->input('email'));

        $email = $request->input('email');

        $link = "http://note-to-myself.app:8000/activate?email=" . $email;

        $text = "To activate your account please follow this link and login.";
        $html = "<h1><strong>Note To Myself</strong></h1></br>
                To activate your account please follow this link and login.
                <a href=$link>link.</a>";

        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl");
        $transport->setUsername('rshellborndev@gmail.com');
        $transport->setPassword('superCool1');
        $swift = \Swift_Mailer::newInstance($transport);

        $message = new \Swift_Message($subject);
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
    }
}
