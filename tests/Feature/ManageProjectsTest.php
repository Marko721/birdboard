<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{

    use WithFaker;
    use RefreshDatabase; //vraca bazu u prethodno stanje
    
    /** @test */

    public function guests_cannot_manage_projects() {

        $project = factory('App\Project')->create(); // given we have a project

        //$attributes = factory('App\Project')->raw();

        $this->get('/projects')->assertRedirect('login'); // if you try to access the dashboard or index as guest of all projects you should be redirected
        $this->get('/projects/create')->assertRedirect('login'); // if you try to access the create project page as guest you should be redirected
        $this->post('/projects', $project->toArray())->assertRedirect('login'); // if you try to persist new project to the database as guest you should be redirected
        $this->get($project->path())->assertRedirect('login'); // if you try to access the project specifically as guest you should be redirected

        

        $this->get($project->path())->assertRedirect('login');

    } 

    /** @test */

    public function a_user_can_create_a_project() {

        $this->withoutExceptionHandling(); //whenever error is thrown, show us the exact error dont try to handle it

        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $attributes = [

            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph

        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);

    }

    /** @test */

    public function a_user_can_view_their_project() {

        $this->signIn();

        $this->withoutExceptionHandling();

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);

        $this->get($project->path())->assertSee($project->title)->assertSee($project->description); //$project->path() je ustvari '/projects/{$project->id}
        // $this->get('/projects/' . $project->id)
        //     ->assertSee($project->title)
        //     ->assertSee($project->description);

    }

    /** @test */

    public function an_authenticated_user_cannot_view_the_projects_of_others() {

        $this->signIn();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);

    }


    /** @test */

    public function a_project_requires_a_title() {

        $this->signIn();
        //$this->actingAs(factory('App\User')->create()); //create a brand new person and set that as an authenticated

        $attributes = factory('App\Project')->raw(['title' => '']); //ensure that the title is an empty string ep.3

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');

    }

    /** @test */

    public function a_project_requires_a_description() {

        $this->signIn();

        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');

    }

}
