<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Task;
use App\Project;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    function it_belongs_to_a_project() {

        $task = factory(task::class)->create(); // given i have a task

        $this->assertInstanceOf(Project::class, $task->project); // if i try to grab a project associated with it, that should be a instance of project


    }

    /** @test */

    function it_has_a_path() {

        $task = factory(Task::class)->create();

        $this->assertEquals('/projects/' . $task->project->id . '/tasks/' . $task->id, $task->path());

    }
}
