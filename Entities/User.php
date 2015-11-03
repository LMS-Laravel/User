<?php

namespace Modules\User\Entities;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Modules\Course\Entities\Lesson;
use Modules\Dashboard\Entities\Country;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, SluggableInterface
{
    use SluggableTrait, Authenticatable, CanResetPassword, EntrustUserTrait;
    
    protected $table = 'users';

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'country_id',
        'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $sluggable = [
        'build_from' => 'username',
        'save_to'    => 'slug',
    ];

    public function roles() {

        return $this->belongsToMany('Modules\User\Entities\Role', 'role_user');
    }

    public function country(){

        return $this->belongsTo(Country::class);
    }

    public function getFullNameAttribute()
    {

        return $this->first_name .' '. $this->last_name;
    }

    public function getCreatedAtAttribute($attr)
    {

        return Carbon::parse($attr)->toFormattedDateString(); //Change the format to whichever you desire
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class)->withTimestamps();
    }
}
