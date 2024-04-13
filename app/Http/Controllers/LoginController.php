<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index(Request $request){
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = DB::table('users')->where('email', $request['email'])->get()->first();
        $hashedPassword = $user->password;

        if ($user && Hash::check($request['password'], $hashedPassword)){
            return response()->json(['id' => $user->id]);
            
        }
        return response()->json(['error' => 'Usuário não encontrado'], 404);
    }

    public function show(Request $request){

    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        var_dump($user);

        if ($user && password_verify($request->password, $user->password)){
            echo "Salv";
            return response()->json(['id' => $user->id]);
        }

        return redirect()->route('login.index')->withErrors(['error' => 'email or password invalid']);
    }
}
