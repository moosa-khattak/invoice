<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(UserRequest $request){
      $user = User::create([
        "name"=>$request->name,
        "email"=>$request->email,
        "passwaord"=>$request->password,
       ]) ;
      if($user){
        return redirect()->route("login")->with("success", "user successfully registered ");
      }
    }
}
