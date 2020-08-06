<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\User;

class UserRegisterController extends Controller
{
	/**
	 * Handle user register functionality.
	 * 
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function __invoke(Request $request)
	{
		$data = $request->validate([
			'firstname' => 'required|string|max:48',
			'lastname' => 'required|string|max:48',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:6|confirmed',
		]);
		
		$user = User::create($data);
		
		$token = Auth::attempt([
			'email' => $data['email'],
			'password' => $data['password'],
		]);

		return response()
			->json([
				'token' => [
					'type' => 'Bearer',
					'token' => $token,
					'expires_in' => auth()->factory()->getTTL() * 60,
				],
				'user' => new UserResource($user),
			], 201);
	}
}
