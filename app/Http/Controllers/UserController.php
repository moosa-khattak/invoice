<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function register(UserRequest $request){
      $user = $this->userRepository->create($request->validated());
      
      if($user){
        return redirect()->route("login")->with("success", "user successfully registered ");
      }
    }

    public function login(LoginRequest $request){
      $credentials = $request->only('email', 'password');

      if(Auth::attempt($credentials)){
        $request->session()->regenerate();
           return redirect()->route("invoice.create")->with("success","user login successfully ");
      }

      return back()->withErrors([
          'email' => 'The provided credentials do not match our records.',
      ])->onlyInput('email');
    }

    public function logout(){
      Auth::logout();
      return redirect()->route("login")->with("success","user logout successfully ");
    }
}
