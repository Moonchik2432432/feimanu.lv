<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{
    public function users()
    {
        $users = User::orderBy('id', 'desc')->paginate(10);

        return view('admin.users', compact('users'));
    }
}