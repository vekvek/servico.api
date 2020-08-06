<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\User;

class UserLoginTest extends TestCase
{
	use RefreshDatabase, WithFaker;
	
	/** @test */
	public function user_can_login()
	{
		$user = factory(User::class, 1)->create()->first();

		$this->json('POST', route('auth.login'), [
			'email' => $user->email,
			'password' => 'password'
		])
			->assertStatus(200)
			->assertJsonStructure([
				'token' => [
					'type', 'token', 'expires_in'
				],
				'user' => [
					'fullname', 'type', 'email'
				]
			]);
	}

	/** @test */
	public function user_cant_login_with_invalid_credentials()
	{
		$this->json('POST', route('auth.login'), [
			'email' => $this->faker->email,
			'password' => $this->faker->password
		])
			->assertStatus(422)
			->assertJsonStructure(['message']);
	}

	/** @test */
	public function email_field_is_required()
	{
		$this->json('POST', route('auth.login'), array_merge($this->body(), ['email' => '']))
			->assertStatus(422)
			->assertJson([
				'errors' => [
					'email' => ['The email field is required.']
				]
			]);
	}

	/** @test */
	public function email_field_is_valid_email_format()
	{
		$this->json('POST', route('auth.login'), array_merge($this->body(), ['email' => Str::random(10)]))
			->assertStatus(422)
			->assertJson([
				'errors' => [
					'email' => ['The email must be a valid email address.']
				]
			]);
	}

	/** @test */
	public function email_field_exists_in_database()
	{
		$this->json('POST', route('auth.login'), array_merge($this->body(), ['email' => $this->faker->email]))
			->assertStatus(422)
			->assertJson([
				'errors' => [
					'email' => ['The selected email is invalid.']
				]
			]);
	}

	/** @test */
	public function password_field_is_required()
	{
		$this->json('POST', route('auth.login'), array_merge($this->body(), ['password' => '']))
			->assertStatus(422)
			->assertJson([
				'errors' => [
					'password' => ['The password field is required.']
				]
			]);
	}

	/** @test */
	public function password_field_is_longer_than_6_characters()
	{
		$this->json('POST', route('auth.login'), array_merge($this->body(), ['password' => Str::random(5)]))
			->assertStatus(422)
			->assertJson([
				'errors' => [
					'password' => ['The password must be at least 6 characters.']
				]
			]);
	}

	/**
	 * Body for requests.
	 *
	 * @return array
	 */
	protected function body()
	{
		return [
			'email' => $this->faker->email,
			'password' => $this->faker->password,
		];
	}
}
