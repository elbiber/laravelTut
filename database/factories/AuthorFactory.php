<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Author;
use Faker\Generator as Faker;

$factory->define(Author::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->afterCreating(App\Author::class, function ($author, $faker) {
    $author->profile()->save(factory(App\Profile::class)->make());
});

// Instance will be created without saving
$factory->afterMaking(App\Author::class, function ($author, $faker) {
    $author->profile()->save(factory(App\Profile::class)->make());
});
