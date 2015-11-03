<?php namespace Modules\User\Http\Controllers\Learning;

use Modules\Course\Entities\Module;
use Modules\User\Repositories\UserRepository;
use Pingpong\Modules\Routing\Controller;
use Modules\User\Polices\Learning\UserPolice;

class UserController extends Controller {

	/**
	 * @var UserRepository
	 */
	private $user;
	/**
	 * @var UserPolice
	 */
	private $userPolice;

	function __construct(UserRepository $user, UserPolice $userPolice)
	{

		$this->user = $user;
		$this->userPolice = $userPolice;
	}


	public function getProfile($slugOrId)
	{
		$profile = $this->user->findBySlugOrIdOrFail($slugOrId);
		$updatePolice = $this->userPolice->update($profile);
		return theme('user.learning.profile', compact('profile', 'updatePolice'));
	}

	public function getPublicProfile($slugOrId)
	{
		$profile = $this->user->findBySlugOrIdOrFail($slugOrId);

		return theme('user.public.profile', compact('profile'));
	}
	
}