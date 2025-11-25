<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function salesPage()
    {
        return view('employee.sales');
    }
}
