<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AllowedStudentId;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
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
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'education_level' => ['required', 'in:basic,college'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!AllowedStudentId::isAllowed($request->student_id, $request->education_level)) {
            return back()->withErrors(['student_id' => 'Student ID is not allowed, does not match education level, or has already been used.'])->withInput();
        }

        $role = $request->input('role', 'tutee');

        $user = User::create([
            'student_id' => $request->student_id,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $role,
            'education_level' => $request->education_level,
            'password' => Hash::make($request->password),
        ]);

        // mark allowed student id as used
        $allowed = AllowedStudentId::where('student_id', $request->student_id)->first();
        if ($allowed) { $allowed->markUsed(); }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
