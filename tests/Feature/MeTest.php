<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class MeTest extends TestCase
{
	use RefreshDatabase, WithFaker;
	
	/** @test */
	public function user_can_get_data()
	{
		$user = factory(User::class, 1)->create()->first();
		$this->actingAs($user, 'api');
		
		$this->json('GET', route('auth.me'))
			->assertStatus(200)
			->assertJsonStructure(['fullname', 'email', 'type']);
	}
}
