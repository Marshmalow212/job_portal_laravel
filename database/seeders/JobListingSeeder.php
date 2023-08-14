<?php

namespace Database\Seeders;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\EmployerController;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobListingSeeder extends Seeder
{
    use FakerTrait;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $factory = $this->FakerFactory();

        $users = User::all();
        $type = ['Office', 'Remote','Hybrid or Remote'];
        foreach($users as $user){
            if($user->role == 'employer'){
                (new AuthController())->login(
                    new \Illuminate\Http\Request([
                        'email'=>$user->email,
                        'password'=>'user1234',
                        'role'=>'Employer'
                    ])
                );

                for($i=0; $i<5; $i++){
                    (new EmployerController())->jobCreate(
                        new \Illuminate\Http\Request([
                            'title' => $factory->jobTitle(),
                            'description' => $factory->text(),
                            'salary' => $factory->numberBetween(300,1500),
                            'deadline' => $factory->date('Y-m-d'),
                            'type' => $type[random_int(0,2)],   
                            'location' => $factory->city(),
                        ])
                    );

                }
            }
        }
    }
}
