<?php

namespace Database\Seeders;

use Faker\Factory;

trait FakerTrait{
    public static function FakerFactory(){
        return Factory::create();
    }
}