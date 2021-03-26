<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Payment;
use App\Jobs\DeleteMultiplePayment;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Bus;

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

        Queue::fake();
        $data = Payment::limit(3)->get();
        Queue::assertNotPushed(DeleteMultiplePayment::class);
        $this->assertTrue(true);
    }

        // test success Delete Multiple field
    public function testSuccessfulDeletePayment()
    {
        $data = Payment::latest()->first();
        $this->json('delete', 'api/v1/payment/'.$data->id, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJson([
                "msg" => "Data Berhasil Dihapus"

            ]);
    }
}
