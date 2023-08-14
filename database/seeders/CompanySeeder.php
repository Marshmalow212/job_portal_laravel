<?php

namespace Database\Seeders;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CompanyController;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    use FakerTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $factory = $this->FakerFactory();

        $users = User::all();

        foreach($users as $user){
            if($user->role == 'employer'){
                (new AuthController())->login(
                    new \Illuminate\Http\Request([
                        'email'=>$user->email,
                        'password'=>'user1234',
                        'role'=>'Employer'
                    ])
                );
                (new CompanyController())->storeOrUpdate(
                    new \Illuminate\Http\Request([
                        'name'=> $factory->company(),
                        'description'=> $factory->text(),
                        'location'=> $factory->city()
                    ])
                );
            }
        }
    }
}
