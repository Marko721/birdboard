<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_has_a_path() {

        $project = factory('App\Project')->create();

        $this->assertEquals('/projects/' . $project->id, $project->path()); //if we have a project i want to be able to call method path and it should return '/projects/' . $project->id //project.php line 14

    }

    /** @test */
    public function is_belongs_to_an_owner() {

        $project = factory('App\Project')->create();

        $this->assertInstanceOf('App\User', $project->owner);


    }

    /** @test */
    public function it_can_add_a_task() {

        $project = factory('App\Project')->create();

        $task = $project->addTask('Test task');

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));


    }



}
