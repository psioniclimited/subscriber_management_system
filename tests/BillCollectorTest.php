<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Modules\User\Models\User;
class BillCollectorTest extends TestCase
{
	use WithoutMiddleware, DatabaseTransactions;
    
    public function testCreateBillCollector()
    {
    	$user = User::find(6);
        $this->be($user);

         $input = [
		  "name" => "Richard Morgan",
		  "email" => "gazizolo@hotmail.com",
		  "territory_id" => "1",
		  "password" => "123456",
		  "upassword_re" => "123456"
		];

		$output = [
			"name" => "Richard Morgan",
			"email" => "gazizolo@hotmail.com",
			"territory_id" => "1",
		];

        $this->visit('/create_bill_collector')
            ->submitForm('Submit', $input);

        $this->seeInDatabase('users', $output);
        $this->assertTrue(true);
    }
}
