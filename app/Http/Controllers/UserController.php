<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Mengambil semua user, diurutkan dari yang paling baru mendaftar
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }
}