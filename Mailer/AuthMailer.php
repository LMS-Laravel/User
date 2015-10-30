<?php

namespace Modules\User\Mailer;

use App\BaseMail;

class AuthMailer extends BaseMail
{

    public function register($user)
    {
        $subject    = 'Welcome to the site!';
        $view       = 'auth.mail.register';
        $data       = [compact('user')];

        $this->send($user, $view, $data, $subject);
    }

}