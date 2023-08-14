<?php

namespace Database\Seeders;

use App\Http\Controllers\Auth\AuthController;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    use FakerTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $factory = $this->FakerFactory();
        $data = [
            0 => [
                'fullname' => $factory->name('male'),
                'username' => $factory->userName(),
                'email' =>$factory->email(),
                'dob' => $factory->date('Y-m-d','now - 10years'),
                'role'=> 'Employer',
                'password' => 'user1234',
            ],
            1 => [
                'fullname' => $factory->name('male'),
                'username' => $factory->userName(),
                'email' =>$factory->email(),
                'dob' => $factory->date('Y-m-d','now - 10years'),
                'role'=> 'Employer',
                'password' => 'user1234',
            ],
            2 => [
                'fullname' => $factory->name('male'),
                'username' => $factory->userName(),
                'email' =>$factory->email(),
                'dob' => $factory->date('Y-m-d','now - 10years'),
                'role'=> 'Employer',
                'password' => 'user1234',
            ],
        ];
        
        foreach($data as $user){
            $res = (new AuthController())->registration(new \Illuminate\Http\Request($user));
            // echo print_r($res->original['data']->toArray());
        }
        $data = [
            0 => [
                'fullname' => $factory->name('male'),
                'username' => $factory->userName(),
                'email' =>$factory->email(),
                'dob' => $factory->date('Y-m-d','now - 10years'),
                'role'=> 'Job Seeker',
                'password' => 'user1234',
            ],
            1 => [
                'fullname' => $factory->name('male'),
                'username' => $factory->userName(),
                'email' =>$factory->email(),
                'dob' => $factory->date('Y-m-d','now - 10years'),
                'role'=> 'Job Seeker',
                'password' => 'user1234',
            ],
            2 => [
                'fullname' => $factory->name('male'),
                'username' => $factory->userName(),
                'email' =>$factory->email(),
                'dob' => $factory->date('Y-m-d','now - 10years'),
                'role'=> 'Job Seeker',
                'password' => 'user1234',
            ],
        ];
        
        foreach($data as $user){
            $res = (new AuthController())->registration(new \Illuminate\Http\Request($user));
            // echo print_r($res->original["data"]->toArray());
        }
    }
}
