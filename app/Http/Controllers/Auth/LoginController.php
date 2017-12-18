<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;

class LoginController extends Controller
{

	/*
	|--------------------------------------------------------------------------
	| Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles authenticating users for the application and
	| redirecting them to your home screen. The controller uses a trait
	| to conveniently provide its functionality to your applications.
	|
	*/

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = '/';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest')->except('logout');
	}

	public function redirectToProvider()
	{
		return Socialite::driver('github')->redirect();
	}

	public function handleProviderCallback()
	{
		return $this->authentizeUser();
	}

	private function authentizeUser()
	{
		$userFromGithub = Socialite::driver('github')->user();

		$user = \App\User::firstOrCreate(
			[
				'username' => $userFromGithub->getEmail(),
			],
			[
				'name'     => $userFromGithub->getName(),
				'password' => password_hash('adsfasdf', PASSWORD_DEFAULT),
				'active'   => true,
			]
		);

		\Illuminate\Support\Facades\Auth::login($user, true);

		return redirect($this->redirectTo);
	}

	public function username(): string
	{
		return 'username';
	}
}
