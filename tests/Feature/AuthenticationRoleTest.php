<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        // Create role and user
        $role = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);
        $user->assignRole('admin');

        $response = $this->post('/login', [
            'userEmail' => 'test@example.com',
            'userPassword' => 'password123',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'userEmail' => 'wrong@example.com',
            'userPassword' => 'wrongpassword',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $this->assertGuest();
    }

    public function test_sales_user_is_redirected_to_profile()
    {
        // Create role and user
        $role = Role::firstOrCreate(['name' => 'sales', 'guard_name' => 'web']);
        $user = User::factory()->create([
            'email' => 'sales@example.com',
            'password' => bcrypt('password123'),
            'role' => 'sales', // Set role field
        ]);
        $user->assignRole('sales');

        $response = $this->post('/login', [
            'userEmail' => 'sales@example.com',
            'userPassword' => 'password123',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect('/profile/' . $user->id);
    }

    public function test_logout_clears_session()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/logout', [
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}