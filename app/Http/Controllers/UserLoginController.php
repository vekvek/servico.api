<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
	/**
	 * Handle user login functionality.
	 * 
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function __invoke(Request $request)
	{
		$credentials = $request->validate([
			'email' => ['required', 'string', 'email', 'max:255', 'exists:users'],
			'password' => ['required', 'string', 'min:6'],
		]);
		
		if (!$token = Auth::attempt($credentials)) {
			return response()
				->json(['message' => 'Invalid credentials!'], 401);
		}
		
		return response()
			->json([
				'token' => [
					'type' => 'Bearer',
					'token' => $token,
					'expires_in' => auth()->factory()->getTTL() * 60,
				],
				'user' => new UserResource(Auth::user()),
			], 200);
	}
}
