<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
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

    if (is_null($request->input('g-recaptcha-response'))) {
      return redirect()->back()->withErrors(['captcha' => 'Tolong lengkapi captcha yang ada']);
    } else {
      $this->authenticate($request);
    }

    $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    if(Auth::attempt([$fieldType => $credentials['username'], 'password' => $credentials['password']])){
      return redirect()->route('dashboards.index');
    }

    return back()->withErrors(['username' => 'Mohon cek kembali username atau password anda!'])->withInput();
  }

  function authenticate(Request $request)
  {
      $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
          'secret'   => env('RECAPTCHA_SITE_SECRET'),
          'response' => $request->input('g-recaptcha-response'),
          'remoteip' => $request->ip(),
      ]);

      $captchaResponse = $response->json();

      if ($captchaResponse['success']) {
        return true;
      } else {
          return redirect()->back()->withErrors(['captcha' => 'Captcha verification gagal.']);
      }
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
