<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Laravel\Passport\HasApiTokens;
use Illuminate\Hashing\BcryptHasher;
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','email','user_sid','avatar','age','password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($password)
        {

            $this->attributes['password'] =  (new BcryptHasher())->make($password);
        }

        public function userCreates(){
        return $this->hasMany(Phrase::class,'user_id');
    }
  
    public function phraseApproveds(){
        return $this->belongsTo(Phrase::class,'user_approved');
    }
}
