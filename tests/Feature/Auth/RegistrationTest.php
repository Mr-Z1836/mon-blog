<?php

namespace Tests\Feature\Auth;

use App\Mail\VerifyEmailMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'username' => 'test_user',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice', absolute: false));
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'username' => 'test_user',
            'name' => 'test_user',
            'email_verified_at' => null,
        ]);

        Mail::assertSent(VerifyEmailMail::class, function (VerifyEmailMail $mail): bool {
            return str_contains($mail->verificationUrl, 'verify-email');
        });
    }
}
