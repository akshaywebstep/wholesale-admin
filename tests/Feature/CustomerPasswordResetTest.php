<?php

namespace Tests\Feature;

use App\Models\EmailConfig;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class CustomerPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        EmailConfig::create([
            'module'        => 'customer',
            'action'        => 'forgot-password',
            'subject'       => 'Forgot Password - Carolina Prime Wholesale',
            'html_template' => '<p>Hello {name}, reset your password here: <a href="{reset_link}">Reset</a></p>',
            'status'        => 1,
        ]);

        $this->customer = User::create([
            'name'      => 'Test Customer',
            'email'     => 'customer@test.com',
            'password'  => Hash::make('InitialPassword123!'),
            'user_type' => 'CUSTOMER',
            'status'    => 'ACTIVE',
        ]);
    }

    public function test_forgot_password_screen_can_be_rendered(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertSee('Forgot Password');
        $response->assertSee('Work / Business Email');
    }

    public function test_non_customer_cannot_request_password_reset(): void
    {
        $admin = User::create([
            'name'      => 'Admin User',
            'email'     => 'admin_test@test.com',
            'password'  => Hash::make('AdminPass123!'),
            'user_type' => 'ADMIN',
            'status'    => 'ACTIVE',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => $admin->email,
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_customer_can_request_password_reset_link(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => $this->customer->email,
        ]);

        $response->assertSessionHas('success');
    }

    public function test_reset_password_screen_cannot_be_rendered_with_invalid_token(): void
    {
        $response = $this->get(route('password.reset', [
            'token' => 'invalid-token-12345',
            'email' => $this->customer->email,
        ]));

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHasErrors('email');
    }

    public function test_reset_password_screen_can_be_rendered_with_valid_token(): void
    {
        $token = Password::broker()->createToken($this->customer);

        $response = $this->get(route('password.reset', [
            'token' => $token,
            'email' => $this->customer->email,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Set New Password');
        $response->assertSee($this->customer->email);
    }

    public function test_customer_password_can_be_reset_with_valid_token(): void
    {
        $token = Password::broker()->createToken($this->customer);
        $newPassword = 'NewSecretPassword123!';

        $response = $this->post(route('password.update'), [
            'token'                 => $token,
            'email'                 => $this->customer->email,
            'password'              => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        // Customer's password was updated in DB
        $this->customer->refresh();
        $this->assertTrue(Hash::check($newPassword, $this->customer->password));

        // Token was invalidated
        $this->assertFalse(Password::broker()->tokenExists($this->customer, $token));
    }
}
