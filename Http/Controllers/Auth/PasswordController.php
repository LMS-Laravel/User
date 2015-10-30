<?php namespace Modules\User\Http\Controllers\auth;

use Illuminate\Http\Request;
use Pingpong\Modules\Routing\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

		return \Theme::view('auth.reset')->with('token', $token);
	}

}