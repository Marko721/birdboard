<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Database\Eloquent\Collection;

class UserTest extends TestCase
{

    use RefreshDatabase;

    /** @test */

    public function a_user_has_projects() {

        $user = factory('App\User')->create(); //given that i have a user

        $this->assertInstanceOf(Collection::class, $user->projects); //a user can access their projects, so that should give us an instance of eloquent collection class


    }
}
