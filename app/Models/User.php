<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $rules = [
        'email' => 'email|unique:users',
    ];

    public function oauthAccessTokens(){
        return $this->hasMany(OauthAccessToken::class);
    }

    public function admin(){
        return $this->hasOne(Admin::class);
    }

    public function company(){
        return $this->hasOne(Company::class);
    }

    public function populate($req, $role, $action = 'store'){
        try {
            $this->email = $req->email;
            $this->role = $role;
            if (!is_null($req->password)){
                $this->password = bcrypt($req->password);
            }
            if (!$this->save()){
                throw new \Exception('Failed to '.$action);
            }
            return $this;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public static function storeExec($req, $role = 3){
        try {
            $model = new self();
            $validator = Validator::make($req->all(), $model->rules);
            if ($validator->fails()){
                throw new \Exception($validator->errors()->first());
            }
            $res = $model->populate($req, $role);
            if (is_string($res)){
                throw new \Exception($res);
            }
            return $model;
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }

}
