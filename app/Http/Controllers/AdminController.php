<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Dahsboard.
     *
     * @return array
     */
    public function index()
    {
        $user = Auth::user();

        return view('index');
    }

}
