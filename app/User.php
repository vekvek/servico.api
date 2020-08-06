<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
	use Notifiable;
	
	/** @var array  */
	protected $fillable = [
		'firstname', 'lastname', 'email', 
		'password', 'type',
	];
	
	/** @var array */
	protected $hidden = [
		'password', 'remember_token',
	];
	
	/** @var array */
	protected $attributes = [
		'type' => 0
	];
	
	/** @var array  */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 *
	 * @return mixed
	 */
	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 *
	 * @return array
	 */
	public function getJWTCustomClaims()
	{
		return [];
	}

	/**
	 * Automatically hash plain user password.
	 * 
	 * @param $value
	 */
	public function setPasswordAttribute($value) {
		$this->attributes['password'] = Hash::make($value);
	}

	/** @var array */
	public const TYPES = [
		0 => 'customer',
		1 => 'servicer',
	];
	
	/**
	 * Return proper type name for user.
	 *
	 * @return mixed|string
	 */
	public function getTypeAttribute()
	{
		return self::TYPES[$this->attributes['type']];
	}

	/** @return string  */
	public function getFullNameAttribute()
	{
		return "{$this->firstname} {$this->lastname}";
	}
}
