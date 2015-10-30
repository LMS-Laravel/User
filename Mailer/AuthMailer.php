<?php

namespace Modules\User\Mailer;

use App\BaseMail;

class AuthMailer extends BaseMail
{

    public function register($user)
    {
        $subject    = trans('user::mail.register.welcome', ['name' =>\Config::get('lms.name')]);
        $view       = 'auth.mail.register';
        $data       = [compact('user')];

        $this->send($user, $view, $data, $subject);
    }

}