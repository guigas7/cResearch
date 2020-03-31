<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Patient;
use Faker\Generator as Faker;

$factory->define(Patient::class, function (Faker $faker) {
    return [
    	 = Patient::all()->orderBy('id', 'DESC')->first()->
        'order' => ,
        'hospital_id' => '',
        'ventilator' => '',
        'name' => '',
        'slug' => '',
        'study' => '',
        'inserted_on' => now(),
    ];
});
