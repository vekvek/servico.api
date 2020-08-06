<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\User;

class UserRegisterTest extends TestCase
{
	use RefreshDatabase, WithFaker;
	
	/** @test */
	public function guest_can_register()
	{
		$this->json('POST', route('auth.register'), $this->body())
			->assertStatus(201)
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
	public function firstname_field_is_required()
	{
		$this->json('POST', route('auth.register'), array_merge($this->body(), ['firstname' => '']))
			->assertStatus(422)
			->assertJson([
				'errors' => [
					'firstname' => ['The firstname field is required.']
				]
			]);
	}

	/** @test */
	public function lastname_field_is_required()
	{
		$this->json('POST', route('auth.register'), array_merge($this->body(), ['lastname' => '']))
			->assertStatus(422)
			->assertJson([
				'errors' => [
					'lastname' => ['The lastname field is required.']
				]
			]);
	}

	/** @test */
	public function firstname_field_is_not_longer_than_48_characters()
	{
		$this->json('POST', route('auth.register'), array_merge($this->body(), ['firstname' => Str::random(49)]))
			->assertStatus(422)
			->assertJson([
				'errors' => [
					'firstname' => ['The firstname may not be greater than 48 characters.']
				]
			]);
	}
	
	/** @test */
	public function lastname_field_is_not_longer_than_48_characters()
	{
		$this->json('POST', route('auth.register'), array_merge($this->body(), ['lastname' => Str::random(49)]))
			->assertStatus(422)
			->assertJson([
				'errors' => [
					'lastname' => ['The lastname may not be greater than 48 characters.']
				]
			]);
	}

	/** @test */
	public function email_field_is_required()
	{
		$this->json('POST', route('auth.register'), array_merge($this->body(), ['email' => '']))
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
		$this->json('POST', route('auth.register'), array_merge($this->body(), ['email' => Str::random(10)]))
			->assertStatus(422)
			->assertJson([
				'errors' => [
					'email' => ['The email must be a valid email address.']
				]
			]);
	}

	/** @test */
	public function email_field_is_unique()
	{
		$user = factory(User::class, 1)->create()->first();

		$this->json('POST', route('auth.register'), array_merge($this->body(), ['email' => $user->email]))
			->assertStatus(422)
			->assertJson([
				'errors' => [
					'email' => ['The email has already been taken.']
				]
			]);
	}

	/** @test */
	public function password_field_is_required()
	{
		$this->json('POST', route('auth.register'), array_merge($this->body(), ['password' => '']))
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
		$this->json('POST', route('auth.register'), array_merge($this->body(), ['password' => Str::random(5)]))
			->assertStatus(422)
			->assertJson([
				'errors' => [
					'password' => ['The password must be at least 6 characters.']
				]
			]);
	}

	/** @test */
	public function password_field_is_confirmed()
	{
		$this->json('POST', route('auth.register'), array_merge($this->body(), ['password_confirmation' => Str::random(10)]))
			->assertStatus(422)
			->assertJson([
				'errors' => [
					'password' => ['The password confirmation does not match.']
				]
			]);
	}
	
	protected function body()
	{
		return [
			'firstname' => $this->faker->firstname,
			'lastname' => $this->faker->lastname,
			'email' => $this->faker->email,
			'password' => 'password',
			'password_confirmation' => 'password',
		];
	}
}
