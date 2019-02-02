<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TasksTest extends TestCase
{
	use WithFaker, RefreshDatabase;

	/** @test */
	public function a_task_can_be_created()
	{	
		$attributes = factory('App\Task')->raw();

		$this->post('/api/tasks', $attributes);

		$this->assertDatabaseHas('tasks', $attributes);
	}

	/** @test */
	public function a_task_requires_a_title()
	{
		$attributes = factory('App\Task')->raw(['title' => '']);

		$this->post('/api/tasks', $attributes)->assertJsonValidationErrors('title');
	}

	/** @test */
	public function a_task_requires_a_description()
	{
		$attributes = factory('App\Task')->raw(['description' => '']);

		$this->post('/api/tasks', $attributes)->assertJsonValidationErrors('description');
	}

	/** @test */
	public function a_task_can_be_viewed()
	{
		$task = factory('App\Task')->create();

		$this->get($task->path())->assertSee($task->title)->assertSee($task->description);
	}

	/** @test */
	public function only_authenticated_users_can_make_a_task()
	{
		$this->withoutExceptionHandling();
		factory('App\User')->create();
		$attributes = factory('App\Task')->raw();

		$this->post('/api/tasks', $attributes)->assertStatus(200);
	}
}
