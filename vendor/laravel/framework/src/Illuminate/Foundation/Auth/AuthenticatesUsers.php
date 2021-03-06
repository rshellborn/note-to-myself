<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;

trait AuthenticatesUsers
{
    use RedirectsUsers, ThrottlesLogins;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);


        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        if($user != null) {

            //check if account is locked
            if ($user->status == 'locked') {
                return $this->sendFailedLoginResponse($request);
            }

            if ($user->active == 'no') {
                return $this->sendFailedLoginResponse($request);
            }
        }



        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $this->lockAccount($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function lockAccount($request) {
        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        //set status to locked
        $user->status = 'locked';

        //generating random pass
        $password = $this->randomPassword();

        //hash the password
        $hashedPassword = \Hash::make($password);

        //change password in database
        $user->password = $hashedPassword;

        $user->save();


        //send email to user
        $this->sendLockoutEmail($request, $password);
    }

    function randomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function sendLockoutEmail($request, $password) {
        require_once '/home/vagrant/Code/note-to-myself/vendor/swiftmailer/swiftmailer/lib/swift_init.php';
        require_once "/home/vagrant/Code/note-to-myself/vendor/swiftmailer/swiftmailer/lib/swift_required.php";

        $subject = 'Note To Myself - Password Reset';
        $from = array('rshellborndev@gmail.com' =>'Admin');
        $to = array($request->input('email'));

        $text = "Your account has been locked. Please follow this link and enter in this password to unlock your account.";
        $html = "<h1><strong>Note To Myself</strong></h1></br>
                Your account has been locked. Please follow this 
                <a href='http://note-to-myself.app:8000/unlock'>link</a> and 
                enter in the password below to unlock your account.<br/><br/>
                
                <strong>Generated Password: </strong>
                $password";

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

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required', 'password' => 'required',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->has('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        session_start();
        $_SESSION["timeout"] = time();
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        if($user != null) {
            //check if account is locked
            if ($user->status == 'locked') {
                return redirect()->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors([
                        $this->username() => Lang::get('auth.locked'),
                    ]);
            }

            if ($user->active == 'no') {
                return redirect()->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->withErrors([
                        $this->username() => Lang::get('auth.notactive'),
                    ]);
            }
        }


            return redirect()->back()
                ->withInput($request->only($this->username(), 'remember'))
                ->withErrors([
                $this->username() => Lang::get('auth.failed'),
            ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return view('auth.login');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
