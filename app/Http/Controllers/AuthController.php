<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
  public function login(){
    return view('auths.login');
  }
  
  public function login_action(Request $request){
    $credentials = $request->validate([
      'username' => 'required',
      'password' => 'required'
    ]);

    $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    if(Auth::attempt([$fieldType => $credentials['username'], 'password' => $credentials['password']])){
      return redirect()->route('dashboards.index');
    }

    return back()->withErrors(['username' => 'Mohon cek kembali username atau password anda!'])->withInput();
  }
  
  public function reset_password(){
    return view('auths.reset_password');
  }
  
  public function reset_password_action(Request $request){
    
  }
  
  public function change_password(){
    return view('auths.change_password');
  }

  public function change_password_action(Request $request){

  }

  public function logout_action(Request $request){
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('auth.login');
  }
}
