<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    // Show form to create new admin user
    public function create()
    {
        return view('admin.admins.create');
    }

    // Handle POST request to store new admin user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => ['required', 'string', 'max:12', 'regex:/^\d+$/', 'unique:users,mobile'],
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'is_admin' => 1,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin user created successfully.');
    }

    // List all admin users
    public function index()
    {
        $admins = User::where('is_admin', 1)->get();
        return view('admin.admins.index', compact('admins'));
    }

    // Delete an admin user
    public function destroy(User $admin)
    {
        if ($admin->id === auth()->id()) {
            return redirect()->route('admin.admins.index')->withErrors('You cannot delete your own account.');
        }
        if ($admin->is_admin) {
            $admin->delete();
            return redirect()->route('admin.admins.index')->with('success', 'Admin user deleted successfully.');
        }
        return redirect()->route('admin.admins.index')->withErrors('User is not an admin.');
    }
}
