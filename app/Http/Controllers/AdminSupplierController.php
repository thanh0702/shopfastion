<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class AdminSupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    // Show form to create new supplier
    public function create()
    {
        return view('admin.suppliers.create');
    }

    // Handle POST request to store new supplier
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'contact_person' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Supplier::create($request->all());

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier created successfully.');
    }

    // List all suppliers
    public function index()
    {
        $suppliers = Supplier::all();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    // Show form to edit supplier
    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    // Handle PUT request to update supplier
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->_id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'contact_person' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $supplier->update($request->all());

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    // Delete supplier
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
