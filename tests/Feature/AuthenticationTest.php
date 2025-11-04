<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'userEmail' => 'test@example.com',
            'userPassword' => 'password123',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->post('/login', [
            'userEmail' => 'invalid@example.com',
            'userPassword' => 'wrongpassword',
            '_token' => csrf_token(),
        ]);

        // Check that it redirects back with error
        $response->assertRedirect();
        $response->assertSessionHasErrors('userEmail');
    }

    public function test_sales_user_is_redirected_to_profile()
    {
        // Create role if not exists
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'sales']);
        
        $salesUser = User::factory()->create([
            'email' => 'sales@example.com',
            'password' => bcrypt('password123'),
            'role' => 'sales', // Set role field
        ]);
        $salesUser->assignRole('sales');

        $response = $this->post('/login', [
            'userEmail' => 'sales@example.com',
            'userPassword' => 'password123',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect('/profile/' . $salesUser->id);
    }

    public function test_session_validation_middleware_works()
    {
        $user = User::factory()->create();
        
        // Test with valid session
        $this->actingAs($user);
        Session::put('user_id', $user->id);
        Session::put('user_role', 'user');
        
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_logout_clears_session()
    {
        $user = User::factory()->create();
        
        $this->actingAs($user);
        Session::put('user_id', $user->id);
        Session::put('user_role', 'user');
        
        $response = $this->get('/logout', ['_token' => csrf_token()]);
        
        $response->assertRedirect('/login');
        $this->assertGuest();
        $this->assertFalse(Session::has('user_id'));
        $this->assertFalse(Session::has('user_role'));
    }
}