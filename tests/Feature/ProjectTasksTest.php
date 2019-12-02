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

    public function only_the_owner_of_a_project_may_update_a_task() {

        $this->signIn(); // you are signed in

        $project = factory('App\Project')->create(); // have a project that you did not create

        $task = $project->addTask('test task'); // and that project has a task called test task

        $this->patch($task->path(), ['body' => 'changed'])  // if we submit a patch request to update it that should fail
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']); // double checking that there should be no record of the change
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

    public function a_task_can_be_updated() {

        $this->withoutExceptionHandling();

        $this->signIn(); //given we are signed in

        $project = auth()->user()->projects()->create( // given the signed in user has a project
            factory('App\Project')->raw()
        );

        $task = $project->addTask('test task'); // given a project has a task

        $this->patch($project->path(). '/tasks/' . $task->id, [ //now when i try to update it from outside, if we try to change the body and we want to set its completed status to true
            'body' => 'changed',
            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks', [ // we should see this in the database tasks table 
            'body' => 'changed',
            'completed' => true
        ]);

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
