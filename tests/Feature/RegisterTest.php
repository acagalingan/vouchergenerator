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

class RegisterTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example. Will add more test if time permits
     */
    public function testUserCanRegisterAndGenerateVoucherCode()
    {
        $payload = [
            "first_name" => "Austine",
            "email" => "acagalingan+12345@gmail.com",
            "username" => "enitsua21496",
            "password" => "test1234"
        ];

        $response = $this->post('/api/register', $payload);

        $response->assertStatus(201);
        $data = json_decode($response->getContent());
        
        $this->assertNotEmpty($data->user);
        $this->assertNotEmpty($data->voucher);
        $this->assertNotEmpty($data->voucher->code);
    }
}
