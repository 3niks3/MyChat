<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function home()
    {
        dd('admin home');
    }

    public function loginAction()
    {
        $credentials = request()->only('email', 'password');

        if (Auth::attempt($credentials)) {
            request()->session()->regenerate();
            // Authentication passed...
            return redirect()->route('admin_home');
        }

        return redirect()->back()->withErrors(['Authorization failed! Incorrect Email or Password']);
    }
}
