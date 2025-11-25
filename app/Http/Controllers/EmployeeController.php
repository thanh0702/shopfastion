<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class EmployeeController extends Controller
{
    public function salesPage(Request $request)
    {
        $categories = Category::all();

        $query = Product::query();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        $products = $query->paginate(20);

        return view('employee.sales', compact('categories', 'products'))
            ->with('search', $request->search)
            ->with('selectedCategory', $request->category);
    }
}
