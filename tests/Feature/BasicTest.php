<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BasicTest extends TestCase
{
	/** @test */
	public function basic_request()
	{
		$this->get(route('index'))
			->assertStatus(200)
			->assertSee('Welcome!');
	}
}
