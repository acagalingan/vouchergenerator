<?php

namespace Tests\Feature;

use App\Models\{
    User,
    Voucher
};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Will add more tests if time permits
     */
    public function testUserCanLoginUsingCorrectCredentials()
    {
        $password = 'test1234';
        $user = User::create([
            'username' => 'acagalingan',
            'first_name' => 'Austine',
            'email' => 'acagalingan@gmail.com',
            'password' => Hash::make($password),
        ]);

        $response = $this->post('/api/login', [
            'username' => $user->username,
            'password' => $password,
        ]);

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($user);
    }

    public function testUserCannotLoginUsingInCorrectCredentials()
    {
        $password = 'test1234';
        $wrongPassword = 'test12345';
        $user = User::create([
            'username' => 'sheaa',
            'first_name' => 'Shea',
            'email' => 'acagalingan+shea@gmail.com',
            'password' => Hash::make($password),
        ]);

        $response = $this->post('/api/login', [
            'username' => $user->username,
            'password' => $wrongPassword,
        ]);

        $response->assertStatus(401);
    }
}
