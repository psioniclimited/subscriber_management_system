<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Modules\User\Models\User;
use App\Modules\User\Models\RoleUser;
class RoleTest extends TestCase
{
    public function testRoleQuery(){
        $users = RoleUser::join('users', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->join('territory', 'users.territory_id', '=', 'territory.id')->get();
//            ->select(['users.id as id', 'users.name', 'users.email', 'roles.display_name', 'territory.name as territory_name']);

        $new_user = User::with('roles', 'territory')
//            ->select(['users.id as id', 'users.name', 'users.email', 'roles.display_name', 'territory.name as territory_name'])
            ->get();

        if($users->diff($new_user) == []){
            $this->assertTrue(true);
        }

    }
}
