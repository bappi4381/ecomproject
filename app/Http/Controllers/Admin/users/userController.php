<?php

namespace App\Http\Controllers\Admin\users;

use App\Http\Controllers\Controller;
use App\Models\User; // or your User model namespace
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(15); // paginate for easier browsing
        return view('admin.users.index', compact('users'));
    }
}
