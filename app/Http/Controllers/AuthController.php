<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // PAGES
    public function loginPage() {
        return view('auth.login');
    }

    public function registerPage() {
        return view('auth.register');
    }

    public function forgotPage() {
        return view('auth.forgot-password');
    }

    // REGISTER
    public function register(Request $request) {

        $request->validate([
            'firstname' => 'required|string|max:255|min:2|regex:/^[a-zA-Z\s]+$/',
            'lastname' => 'required|string|max:255|min:2|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])/',
        ], [
            'firstname.required' => 'First name is required',
            'firstname.min' => 'First name must be at least 2 characters',
            'firstname.regex' => 'First name can only contain letters and spaces',
            'lastname.required' => 'Last name is required',
            'lastname.min' => 'Last name must be at least 2 characters',
            'lastname.regex' => 'Last name can only contain letters and spaces',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Passwords do not match',
            'password.regex' => 'Password must contain uppercase, lowercase, and numbers',
        ]);

        User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('success', 'Account created successfully. Please log in.');
    }

    // LOGIN
    public function login(Request $request) {

        $credentials = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8'
        ], [
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
        ]);

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password'
        ])->withInput();
    }

    // LOGOUT
    public function logout(Request $request) {

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // SEND RESET LINK
    public function sendReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with('success', __($status))
                    : back()->withErrors(['email' => __($status)]);
    }
}
