<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Vendor;
use App\Models\Customer;
use App\Models\Bill;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;

class VendorPaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function vendor_can_register()
    {
        $response = $this->postJson('/api/v1/vendor/register', [
            'name' => 'New Vendor',
            'email' => 'newvendor@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('vendors', [
            'email' => 'newvendor@example.com',
        ]);
    }

    /** @test */
    public function vendor_can_login()
    {
        $vendor = Vendor::create([
            'name' => 'Test Vendor',
            'email' => 'vendor@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/vendor/login', [
            'email' => 'vendor@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'vendor',
                'token'
            ]
        ]);
    }

    /** @test */
    public function vendor_can_make_payment_for_customer()
    {
        $vendor = Vendor::create([
            'name' => 'Test Vendor',
            'email' => 'vendor@example.com',
            'password' => Hash::make('password123'),
        ]);

        $customer = Customer::create([
            'first_name' => 'John',
            'surname' => 'Doe',
            'email' => 'john@example.com',
            'phone_number' => '1234567890',
            'billing_id' => '123456789',
            'password' => Hash::make('password123'),
        ]);

        $bill = Bill::create([
            'customer_id' => $customer->id,
            'billing_id' => '123456789',
            'amount' => 1000,
            'balance' => 1000,
            'approval_status' => 'approved',
            'status' => 'pending',
        ]);

        // Create a personal access token for the vendor
        $token = $vendor->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/vendor/payment', [
                'billing_id' => $customer->billing_id,
                'amount' => 500,
                'payment_method' => 'cash',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Payment processed successfully',
        ]);

        $this->assertDatabaseHas('payments', [
            'customer_id' => $customer->id,
            'amount' => 500,
            'method' => 'cash',
            'channel' => 'Vendor Payment',
        ]);
    }
}