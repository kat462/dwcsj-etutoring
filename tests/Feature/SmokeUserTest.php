<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeUserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    /** @test */
    public function admin_user_can_login_and_see_dashboard()
    {
        // Arrange: create admin user
        $admin = User::factory()->create([
            'student_id' => 'ADMIN001',
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Act: login as admin
        $response = $this->post('/login', [
            'student_id' => 'ADMIN001',
            'password' => 'password',
        ]);
        $response->dump();

        // Assert: redirected to dashboard
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    /** @test */
    public function tutor_user_can_login_and_see_dashboard()
    {
        // Arrange: create tutor user
        $tutor = User::factory()->create([
            'student_id' => 'TUTOR001',
            'role' => 'tutor',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Act: login as tutor
        $response = $this->post('/login', [
            'student_id' => 'TUTOR001',
            'password' => 'password',
        ]);
        $response->dump();

        // Assert: redirected to dashboard
        $response->assertRedirect('/tutor/dashboard');
        $this->assertAuthenticatedAs($tutor);
    }

    /** @test */
    public function student_user_can_login_and_see_dashboard()
    {
        // Arrange: create student user
        $student = User::factory()->create([
            'student_id' => 'STUDENT001',
            'role' => 'tutee',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Act: login as student
        $response = $this->post('/login', [
            'student_id' => 'STUDENT001',
            'password' => 'password',
        ]);
        $response->dump();

        // Assert: redirected to dashboard
        $response->assertRedirect('/student/dashboard');
        $this->assertAuthenticatedAs($student);
    }
}
