<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminEmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    // Show form to create new employee user
    public function create()
    {
        return view('admin.employees.create');
    }

    // Handle POST request to store new employee user
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
            'is_employee' => true,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.employees.index')->with('success', 'Employee user created successfully.');
    }

    // List all employee users
    public function index()
    {
        $employees = User::where('is_employee', true)->get();
        return view('admin.employees.index', compact('employees'));
    }

    // Optional: add destroy method for deleting employees, similar to AdminUserController
}
