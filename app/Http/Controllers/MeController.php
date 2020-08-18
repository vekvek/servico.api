<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;

class MeController extends Controller
{
	public function __invoke()
	{
		return response()
			->json(new UserResource(auth()->user()), 200);
	}
}
