<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view('login');
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $user = User::where('email', $validatedData['email'])
                    ->where('password', $validatedData['password'])
                    ->first();

        /*$credentials = $request->only('email', 'password');
        $authenticated = Auth::attempt($credentials);*/

        /*if (!$authenticated){
            return redirect()->route('login.index')->withErrors(['error' => 'email or password invalid']);
        }*/
        return response()->json(['id' => $user->id]);
    }
}
