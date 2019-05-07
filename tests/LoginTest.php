<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Modules\User\Models\User;
use Faker\Generator;
class LoginTest extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;

    public function testLogin()
    {
        $this->visit('/')
            ->type('admin@admin.com', 'username')
            ->type('secret', 'password')
            ->press('Sign In')
            ->seePageIs('/dashboard');
    }

    public function testCreateUsers(){
        $user = User::find(6);
        $this->be($user);
        $name = str_random(6);
        $email = str_random(6) . "@gmail.com";
        $input = [
          "fullname" => $name,
          "uemail" => $email,
          "uroles" => "2",
          "territory" => "1",
          "upassword" => "qwerasdf",
          "upassword_re" => "qwerasdf"
        ];
        $this->visit('/create_users')
            ->submitForm('Submit', $input);

        $this->seeInDatabase('users', ['email' => $email]);
    }
}
