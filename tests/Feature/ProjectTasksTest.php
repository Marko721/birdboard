<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class ProjectTasksTest extends TestCase
{

    use RefreshDatabase;

    /** @test */

    public function guests_cannot_add_tasks_to_projects() {

        $project = factory('App\Project')->create();

        $this->post($project->path() . '/tasks')->assertRedirect('login');

    }

    /** @test */

    public function only_the_owner_of_a_project_may_add_tasks() {

        $this->signIn();

        $project = factory('App\Project')->create(); //if i have a project

        $this->post($project->path() . '/tasks', ['body' => 'Test task']) // and i try to add a new task to that project but we dont have a proper permission
            ->assertSee(403); // i expect a 403 status code

        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']); // just to be sure there should be no record in tasks table


    }


    /** @test */

    public function a_project_can_have_tasks() {

        //$this->withoutExceptionHandling();
        $this->signIn();

        //$project = factory('App\Project')->create(['owner_id' => auth()->id()]);
        $project = auth()->user()->projects()->create(
            factory('App\Project')->raw()
        );

        $this->post($project->path() . '/tasks', ['body'=>'Test task']);

        $this->get($project->path())->assertSee('Test task');

    }

    /** @test */

    public function a_task_requires_a_body() {

        $this->signIn();

        $project = auth()->user()->projects()->create(
            factory('App\Project')->raw()
        );

        $attributes = factory('App\Task')->raw(['body' => '']);

        $this->post($project->path() . '/tasks', $attributes)->assertSessionHasErrors('body');


    }


}
