<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AllowedStudentId;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller {
    public function showLogin(){ return view('auth.login'); }
    public function showRegister(){ return view('auth.register'); }
    public function register(Request $req){
        $req->validate([
            'student_id' => 'required|unique:users,student_id',
            'name' => 'required',
            'education_level' => ['required', Rule::in(['basic','college'])],
            'password' => 'required|min:6|confirmed',
            // role optional; default to tutee
        ]);

        if (!AllowedStudentId::isAllowed($req->student_id, $req->education_level)) {
            return back()->withErrors(['student_id' => 'Student ID is not allowed, does not match education level, or has already been used.'])->withInput();
        }

        $role = $req->input('role', 'tutee');

        $user = User::create([
            'student_id' => $req->student_id,
            'name' => $req->name,
            'email' => $req->email,
            'role' => $role,
            'education_level' => $req->education_level,
            'password' => $req->password,
        ]);

        $allowed = AllowedStudentId::where('student_id', $req->student_id)->first();
        if ($allowed) { $allowed->markUsed(); }

        Auth::login($user);
        return redirect()->route('dashboard'); }
    public function login(Request $req){ $req->validate(['student_id'=>'required','password'=>'required']); if(Auth::attempt($req->only('student_id','password'))){ $req->session()->regenerate(); return redirect()->intended(route('dashboard')); } return back()->withErrors(['student_id'=>'Invalid credentials'])->withInput(); }
    public function logout(Request $req){ Auth::logout(); $req->session()->invalidate(); $req->session()->regenerateToken(); return redirect()->route('login'); }
}
