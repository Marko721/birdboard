<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(1), // recenica sa 4 reci
        'description' => $faker->paragraph(1),
        'owner_id' => function() {
            return factory(App\User::class)->create()->id;
        }
    ];
});
