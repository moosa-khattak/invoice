<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;


class UserController extends Controller
{
  public function __construct(
    protected UserRepositoryInterface $userRepository
  ) {}

  public function googlelogin()
  {
    return Socialite::driver("google")->redirect();
  }

  public function googleCallback()
  {
    try {
      $googleUser = Socialite::driver("google")->user();

      $user = User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
          'name'     => $googleUser->getName(),
          'password' => bcrypt(Str::random(24)),
        ]
      );

      // Log the user in to create a session for the 'auth' middleware
      Auth::login($user);

      // Store additional session data as requested
      session()->put("id", $user->id);
      session()->put("type", $user->type);

      return redirect()->route("invoice.create")->with("success", "Logged in with Google successfully!");
    } catch (\Exception $e) {
      return redirect()->route("login")->with("error", "Failed to login with Google: " . $e->getMessage());
    }
  }

  public function register(UserRequest $request)
  {
    $user = $this->userRepository->create($request->validated());

    if ($user) {
      return redirect()->route("login")->with("success", "user successfully registered ");
    }
  }

  public function login(LoginRequest $request)
  {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();
      return redirect()->route("invoice.create")->with("success", "user login successfully ");
    }

    return back()->withErrors([
      'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
  }

  public function logout()
  {
    Auth::logout();
    return redirect()->route("login")->with("success", "user logout successfully ");
  }
}
