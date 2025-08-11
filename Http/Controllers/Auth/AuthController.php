<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    
    protected $tenantId;
    public function __construct()
    {
        $this->tenantId = currentTenantId();
    }

    public function register(Request $request)
    {


        $request->validate(
            [
                'name' => 'required|string|max:25',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|max:25|confirmed',
            ],
            [
                'name.required' => 'Please enter your full name.',
                'name.max' => 'Your name must not exceed 25 characters.',

                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered. Please use a different one.',

                'password.required' => 'Please enter a password.',
                'password.min' => 'Password must be at least 6 characters long.',
                'password.max' => 'Password must not exceed 25 characters.',
                'password.confirmed' => 'Passwords do not match. Please confirm your password correctly.',
            ]
        );

        User::create([
            'tenant_id' => $this->tenantId ?? 1,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Congratulations! You are registered successfully.');
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);
        

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            return redirect()->intended('home')->with('success', 'You have been logged in successfully.');
        }

        return redirect()->back()->withInput()->with('error', 'The provided credentials do not match our records.');
    }


    public function logout()
    {
        Auth::logout();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
