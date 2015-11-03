<?php

namespace Modules\User\Polices\Learning;


use Illuminate\Auth\Guard;

class UserPolice
{

    public function __construct(Guard $auth)
    {
        $this->user = $auth->user();
    }

    public function update($user, $auth = null)
    {
        if($auth==null)
            $auth = $this->user;

        return $user->id == $auth->id;
    }
}