<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Modules\User\Models\User;
class CardTest extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;

    public function testAddCard()
    {
        // $user = User::find(6);
        // $this->be($user);

        // $input = [
        //   "card_id" => "111"
        // ];
        // $this->visit('/add_card')
        //     ->submitForm('Submit', $input);
        // $this->seeInDatabase('cards', ['card_id' => "111"]);
    }
}
