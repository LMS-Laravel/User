<?php

namespace modules\User\Http\Controllers\Auth;

use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Modules\Dashboard\Repositories\CountryRepository;
use Modules\User\Entities\User;
use Modules\User\Mailer\AuthMailer;
use Modules\User\Repositories\UserRepository;
use Pingpong\Modules\Routing\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers;

    protected $loginPath;
    protected $redirectPath;
    protected $redirectTo;
    /**
     * @var UserRepository
     */
    private $user;

    public function __construct(UserRepository $user)
    {
        $this->redirectPath = route('dashboard.learning');
        $this->redirectTo = $this->redirectPath;
        $this->loginPath = route('auth.loginGet');
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->user = $user;
    }

    public function index()
    {
        return \Theme::view('auth.login');
    }

    protected function validator(array $data)
    {
        return \Validator::make($data, [
            'username' => 'required|max:255|unique:users',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'country_id' => 'required|exists:countries,id',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    protected function createUser(array $data)
    {
        return $this->user->create([
            'username' => $data['username'],
            'email' => $data['email'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'country_id' => $data['country_id'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function getRegister(CountryRepository $country)
    {
        $countries = $country->all(['id', 'short_name']);

        return \Theme::view('auth.register', compact('countries'));
    }

    public function postRegister(Request $request, AuthMailer $mailer)
    {

        // Start transaction!
        \DB::beginTransaction();

        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
            \DB::rollback();
        }

        try {
            $user = $this->createUser($request->all());
            $user->roles()->attach(3);
            $mailer->register($user);

            \DB::commit();
            \Auth::login($user);

            return redirect($this->redirectPath());
        } catch (ValidationException $e) {
            \DB::rollback();

            return redirect($this->redirectPath())
                ->withErrors($e->errors())
                ->withInput();
        }
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            Config::get('auth.login') => 'required', 'password' => 'required',
        ]);

        $credentials = $this->getCredentials($request);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            return redirect()->intended($this->redirectPath());
        }

        return redirect($this->loginPath())
            ->withInput($request->only(Config::get('auth.login'), 'remember'))
            ->withErrors([
                Config::get('auth.login') => $this->getFailedLoginMessage(),
            ]);
    }

    public function getLogout()
    {
        Auth::logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

    public function loginPath()
    {
        return property_exists($this, 'loginPath') ? $this->loginPath : '/auth/login';
    }

    public function getCredentials(Request $request)
    {
        return $request->only(Config::get('auth.login'), 'password');
    }

    public function redirectPath()
    {
        if (property_exists($this, 'redirectPath')) {
            return $this->redirectPath;
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/learning';
    }

    public function getFailedLoginMessage()
    {
        return trans('user::ui.login.credentials_error', array('field' => Config::get('auth.login')));
    }
}
