<?php

namespace App\Http\Controllers; // <-- PASTIKAN NAMESPACE INI BENAR

// Mungkin ada use statement lain di sini, tidak masalah
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController; // <-- PASTIKAN INI ADA

class Controller extends BaseController // <-- PASTIKAN INI EXTENDS BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}