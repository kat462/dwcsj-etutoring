<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AllowedStudentId;
use App\Services\AllowedIdAuditService;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use App\Helpers\EducationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'student_id' => ['required', 'string', 'max:255', 'unique:users,student_id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'education_level' => ['required', 'in:' . implode(',', array_keys(EducationLevel::options()))],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Normalize education_level for compatibility with admin panel
        $education_level = $request->education_level;

        if (!AllowedStudentId::isAllowed($request->student_id, $education_level)) {
            return back()->withErrors(['student_id' => 'Student ID is not allowed, does not match education level, or has already been used.'])->withInput();
        }

        $role = $request->input('role', 'tutee');

        $user = User::create([
            'student_id' => $request->student_id,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $role,
            'education_level' => $education_level,
            'password' => Hash::make($request->password),
        ]);

        // mark allowed student id as used
        $allowed = AllowedStudentId::where('student_id', $request->student_id)->first();
        if ($allowed) {
            $allowed->markUsed();
            AllowedIdAuditService::logUsage($allowed, $user, 'used');
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
