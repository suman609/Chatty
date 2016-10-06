<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class AuthController extends Controller {

    // Display our View for Signup page
    public function getSignup() {
        return view('auth.signup');
    }

    // Post data through to this to sign user up
    public function postSignup(Request $request) {
        // Validate Sign up Form
        $this->validate($request, [
            'email' => 'required|unique:users|email|max:255',
            'username' => 'required|unique:users|alpha_dash|max:20',
            'password' => 'required|min:6',
        ]);

        // Create User Account on Sign up.
        User::create([
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password'))
        ]);

        // Then redirect them to Home page with message.
        return redirect()->route('home')->with('info', 'Your account has been created,
                you can now sign in.');

    }

    // Display our View for Sign In page.
    public function getSignin() {
        return view('auth.signin');
    }

    // Post data through to this to sign user in.
    public function postSignin(Request $request) {
        // Validate
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        // If the request to sign in user has failed, give error message.
        if (!Auth::attempt($request->only(['email', 'password']),
            $request->has('remember'))) {
                return redirect()->back()->with('info', 'Could not sign you in with those details');
        }

        // If signed in successful, redirect
        return redirect()->route('home')->with('info', 'Your are now signed in');
    }

    /**
     * Sign user Out, then redirect to home page.
     */
    public function getSignout() {
        Auth::logout();
        return redirect()->route('home');
    }


}