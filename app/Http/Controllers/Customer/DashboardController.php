<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Di sini Anda bisa mengambil data lain yang relevan untuk dashboard pelanggan nanti
        return view('customer.dashboard', compact('user'));
    }
}