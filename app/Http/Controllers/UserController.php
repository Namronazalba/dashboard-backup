<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function user_index()
    {
        return view('user.index');
    }
    public function login()
    {
        return view('user.login');
    }
    public function register()
    {
        return view('user.register');
    }
    public function registerPost(Request $request)
    {
        //  dd($request);
          $validated = $request->validate([
             "name" => ['required','min:4'],
             "email" => ['required', 'email', Rule::unique('users', 'email')],
             "password" => 'required|confirmed|min:6'
          ]);

          $validated['password'] = bcrypt($validated['password']);

          $user = User::create($validated);

        //   $success = auth()->login($user);
          if($user){
            return redirect()->route('login')->with('success', 'Registered successfuly.');
          }

    }
    
    public function loginPost(Request $request)
    {
        //  dd($request);
          $validated = $request->validate([
             "email" => ['required', 'email'],
             "password" => 'required'
          ]);

          if(auth()->attempt($validated)){
            $request->session()->regenerate();
            return redirect('/')->with('success','Login successfully.');
          }
          return back()->withErrors(['email' => 'Login failed.'])->onlyInput('email');

    }
     public function logout(Request $request){
          auth()->logout();
          $request->session()->invalidate();
          $request->session()->regenerateToken();

          return redirect('/login')->with('message','Logout Successfully.');
     }

}
