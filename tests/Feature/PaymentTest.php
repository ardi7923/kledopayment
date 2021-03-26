<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentTest extends TestCase
{

    // test get data
    public function testgetpayment()
    {
      $this->get('api/v1/payment')->assertStatus(200);
    }
    // test required field
    public function testrequiredfieldspayment()
    {
        $this->json('POST', 'api/v1/payment', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "payment_name" => ["The payment name field is required."],
                ]
            ]);
    }

    // test success post field
    public function testSuccessfulPostPayment()
    {
        $data = [
            "payment_name" => "payment name 1",

        ];

        $this->json('POST', 'api/v1/payment', $data, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                "msg" => "Data Berhasil Disimpan"

            ]);
    }

    // test success Delete Multiple field
    public function testSuccessfulDeleteMultiplePayment()
    {
        $data = [
            "ids" => [5,6,7],

        ];

        $this->json('delete', 'api/v1/payment/multiple', $data, ['Accept' => 'application/json', 'Content-Type' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "msg" => "Data Berhasil Dihapus"

            ]);
    }

        // test success Delete Multiple field
    public function testSuccessfulDeletePayment()
    {
        $this->json('delete', 'api/v1/payment/9', ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "msg" => "Data Berhasil Dihapus"

            ]);
    }
}
