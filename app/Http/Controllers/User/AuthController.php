<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showAccountPage()
    {
        return view('frontend.auth.login');
    }

    /**
     * Handle login submission
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))
                             ->with('success', 'Welcome back!');
        }

        return back()->withErrors(['email' => 'Invalid credentials. Please try again.']);
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('frontend.auth.registration');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
        ]);

        // Generate a custom user_id like U-XXXX
        $userId = 'U-' . str_pad(User::count() + 1, 4, '0', STR_PAD_LEFT);

        $user = User::create([
            'user_id'  => $userId,
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'address'  => $request->address,
        ]);

        // Auto login
        Auth::guard('web')->login($user);

        // Optional: Send welcome email
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Exception $e) {
            \Log::error("Welcome mail failed: ".$e->getMessage());
        }

        return redirect()->route('home')->with('success', 'Registration successful!');
    }

    /**
     * Logout the user
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out.');
    }
}
