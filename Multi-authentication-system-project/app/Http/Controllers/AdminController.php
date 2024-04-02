<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        return view('auth.admin.login');

    }

    public function login(Request $request)
    {
        if(Auth::guard('admin')->attempt(['email'=>$request->email, 'password'=>$request->password]))
        {
            return redirect()->route('admin.dashboard')->with('success', 'Login Successfull');
        }
        else
        {
            return redirect()->back()->with('error', 'Invalid Email Or Password');
        }
    }

    public function dashboard()
    {
        return view('admin-dashboard');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        // $request->session()->invalidate();

        // $request->session()->regenerateToken();

        return redirect()->route('admin.login.index');
    }

    public function registerIndex()
    {
        return view('auth.admin.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' =>['required', 'string', 'max:255'],
            'email' =>['required', 'email', 'max:255', 'unique:admins'],
            'password' =>['required', 'string', 'max:8', 'confirmed']
        ]);

        $admin = new Admin();

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->password = bcrypt($request->password);
        $admin->save();

        Auth::guard('admin')->login($admin);
        return redirect()->route('admin.dashboard');
    }
}
