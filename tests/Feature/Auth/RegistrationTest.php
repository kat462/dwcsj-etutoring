<?php
namespace Tests\Feature\Auth;
use Illuminate\Support\Facades\DB;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        // $this->artisan('db:seed', ['--class' => 'Database\\Seeders\\AllowedStudentIdsSeeder']);
    }

    public function test_new_users_can_register(): void
    {
        // Use a unique student_id and email for each run
        $uniqueId = 'DWC' . rand(100, 999);
        $uniqueEmail = 'test' . rand(1000, 9999) . '@example.com';
        // Insert into allowed_student_ids
        DB::table('allowed_student_ids')->insert([
            'student_id' => $uniqueId,
            'education_level' => 'college',
            'used' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $response = $this->post('/register', [
            'student_id' => $uniqueId,
            'name' => 'Test User',
            'email' => $uniqueEmail,
            'education_level' => 'college',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        if (!$response->isRedirection()) {
            fwrite(STDERR, "\nRegistration response:\n" . $response->getContent() . "\n");
        }
        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
