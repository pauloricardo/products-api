<?php
/**
 * Created by PhpStorm.
 * User: paulo
 * Date: 05/12/2017
 * Time: 20:35
 */
use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    public function run() {
        App\User::create([
            'name' => 'User Teste',
            'email' => 'teste@teste.com.br',
            'password' => password_hash('1234', PASSWORD_BCRYPT)
        ]);
    }
}