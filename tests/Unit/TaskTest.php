<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
	/** @test */
	public function it_has_a_path()
	{
		$task = factory('App\Task')->create();

		$this->assertEquals('/api/tasks/' . $task->id, $task->path());
	}
}
