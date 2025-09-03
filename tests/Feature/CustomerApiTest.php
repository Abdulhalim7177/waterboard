<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Staff;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    protected $staff;
    protected $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::create(['name' => 'admin', 'guard_name' => 'staff']);
        $this->staff = Staff::factory()->create();
        $this->staff->assignRole($this->adminRole);
    }

    public function test_can_get_all_customers()
    {
        Customer::factory()->count(5)->create();

        $response = $this->actingAs($this->staff, 'staff')->getJson('/api/v1/customers');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_can_create_customer()
    {
        $customerData = [
            'first_name' => 'John',
            'surname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone_number' => '1234567890',
            'password' => 'password',
            'area_id' => \App\Models\Area::factory()->create()->id,
            'lga_id' => \App\Models\Lga::factory()->create()->id,
            'ward_id' => \App\Models\Ward::factory()->create()->id,
            'category_id' => \App\Models\Category::factory()->create()->id,
            'tariff_id' => \App\Models\Tariff::factory()->create()->id,
        ];

        $response = $this->actingAs($this->staff, 'staff')->postJson('/api/v1/customers', $customerData);

        $response->assertStatus(201)
            ->assertJsonFragment(['email' => 'john.doe@example.com']);

        $this->assertDatabaseHas('customers', ['email' => 'john.doe@example.com']);
    }

    public function test_can_get_a_single_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($this->staff, 'staff')->getJson("/api/v1/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['email' => $customer->email]);
    }

    public function test_can_update_a_customer()
    {
        $customer = Customer::factory()->create();

        $updateData = ['first_name' => 'Jane'];

        $response = $this->actingAs($this->staff, 'staff')->putJson("/api/v1/customers/{$customer->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Update submitted for approval.']);

        $this->assertDatabaseHas('pending_customer_updates', ['customer_id' => $customer->id, 'new_value' => 'Jane']);
    }

    public function test_can_delete_a_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAs($this->staff, 'staff')->deleteJson("/api/v1/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Customer deleted successfully']);

        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
}
