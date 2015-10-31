<?php namespace Modules\User\Http\Controllers\Learning;

use Modules\User\Repositories\UserRepository;
use Pingpong\Modules\Routing\Controller;

class UserController extends Controller {

	/**
	 * @var UserRepository
	 */
	private $user;

	function __construct(UserRepository $user)
	{

		$this->user = $user;
	}


	public function getProfile($slugOrId)
	{
		$profile = $this->user->findBySlugOrIdOrFail($slugOrId);

		return theme('user.learning.profile', compact('profile'));
	}
	
}