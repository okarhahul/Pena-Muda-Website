<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index() {
        return view('register.index', [
            "tittle" => "Registrasi",
            "active" => "register"
        ]);
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'name' => ['required', 'max:255'],
            'username' =>['required', 'min:4', 'max:16', 'unique:users'],
            'email' => ['required', 'email:dns', 'unique:users'],
            'password' => ['required', 'min:8', 'max:255']
        ]); 
        
        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        // $request->session()->flash('success', 'Pendaftaran akun kamu telah berhasil, silahkan login!');

        return redirect('/login')->with('success', 'Pendaftaran akun berhasil! silahkan login');
    }
}
