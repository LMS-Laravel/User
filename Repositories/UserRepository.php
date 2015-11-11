<?php

namespace modules\User\Repositories;

use App\BaseRepository;
use Modules\User\Entities\User;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }
}
