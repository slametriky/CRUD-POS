<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Hash;
use Session;
use DB;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{


    public function showFormLogin()
    {       
        if (Auth::check()) { 
            return redirect()->intended(route('sales-orders.index'));
        }
        return view('login');
    }
   
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required|string'
        ]);

        $credentials = $request->only('email', 'password');
     
        if (Auth::attempt($credentials)) {
            
            $request->session()->regenerate();
     
            return redirect()->intended(route('sales-orders.index'));
        }
     
        return back()->with([
            'error' => 'Email atau Password salah',
        ]);
  
    }

    public function showFormRegister()
    {               
        return view('register');
    }
   
    public function register(RegisterRequest $request)
    {
     
        $user = new User();
        $password = Hash::make($request->password);

        $user->password = $password;
        $user->fill($request->except('password'))->save();        
        
        return Redirect::to('/login')->with([
            'success' => 'Berhasil registrasi, silahkan login!',
        ]);
        
  
    }

}
