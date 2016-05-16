<?php namespace Modules\User\Http\Controllers\auth;

use App\PasswordBroker;
use Illuminate\Http\Request;
use Pingpong\Modules\Routing\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Password;
use App\PasswordBroker as LMSPasswordBroker;

class PasswordController extends Controller {

	use ResetsPasswords;


	/**
	 * PasswordController constructor.
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	public function getEmail()
    {
		return \Theme::view('auth.reset');
	}


	public function redirectPath()
	{
		if (property_exists($this, 'redirectPath')) {
			return $this->redirectPath;
		}

		return property_exists($this, 'redirectTo') ? $this->redirectTo : '/learning';
	}

	public function getReset($token = null)
	{
		if (is_null($token)) {
			throw new NotFoundHttpException;
		}

		return \Theme::view('auth.recover')->with('token', $token);
	}

	/**
	 * Send a reset link to the given user.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function postEmail(Request $request)
	{
		$this->validate($request, ['email' => 'required|email']);

		$response = Password::sendResetLink($request->only('email'), function (Message $message) {
			$message->subject($this->getEmailSubject());
		});

		switch ($response) {
			case Password::RESET_LINK_SENT:
				return redirect()->back()->with('status', trans($response));

			case Password::INVALID_USER:
				return redirect()->back()->withErrors(['email' => trans($response)]);
		}
	}

}