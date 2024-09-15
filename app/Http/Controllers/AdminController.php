<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{



    public function logout()
    {
        auth()->logout();
        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }
}
