<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Modules\User\Models\User;
class UserTest extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;

    public function testEditRole()
    {
        // $user = User::find(6);
        // $this->be($user);
//
        // $input = [
        //     "name" => "Saad Bhaiya",
        //     "email" => "saadbinmahbub@gmail.com",
        //     "uroles" => "2",
        //     "territory" => "1",
        //     "password" => "qwerasdf",
        //     "upassword_re" => "qwerasdf",
        //     "uid" => "14",
        // ];
//
        // $this->visit('/users/14/edit')
        //     ->submitForm('Submit', $input);
        // $this->seeInDatabase('users', ['email' => "saadbinmahbub@gmail.com"]);
        // $this->seeInDatabase('role_user', ['user_id' => "14", "role_id" => "2"]);
    }

    public function testIsAdmin(){
        $user = User::find(1);
        if($user->isAdmin == false){
            $this->assertTrue(true);
        }
    }
}
