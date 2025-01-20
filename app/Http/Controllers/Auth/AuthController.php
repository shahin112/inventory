<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Profile;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{

  
    public function registerForm()
    {
        return Inertia::render("Auth/Register");
    }

    public function register(Request $request)
    {

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:5',
            'store_name' => 'required|min:5',
        ]);


  
        $user = null;

        DB::transaction(function () use ($request, &$user) {

            $store = Store::create([
                'store_name' => $request->store_name,
            ]);

            $user = User::create([
                'store_id' => $store->id,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'role' => 1,
            ]);

            Profile::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,

            ]);
        });

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function LoginForm()
    {
        return Inertia::render("Auth/Login");
    }


    public function login(Request $request)
    {
        $credential = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:4',
        ]);

        if (Auth::attempt($credential, false)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'the provided credentials do not match ',
        ])->onlyInput('email');
    }

   

    public function logout(Request $request)
    {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()
            ->regenerateToken();

        return redirect()->route('login');

    }
}
