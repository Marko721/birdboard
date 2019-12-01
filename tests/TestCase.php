<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function signIn($user = null) { // we accept the user but dont require one

        return $this->actingAs($user ?: factory('App\User')->create()); // given the user or there is no user we will make one

    }

}
