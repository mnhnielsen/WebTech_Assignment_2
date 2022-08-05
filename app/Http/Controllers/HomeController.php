<?php

namespace App\Http\Controllers;

use App\Models\Adoption;
use App\Models\User;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    public function index()
    {
        $adoptions = Adoption::latest()->unadopted()->get();
        return view('adoptions.list', ['adoptions' => $adoptions, 'header' => 'Available for adoption']);
    }

    public function login()
    {
        return view('login');
    }

    public function doLogin(Request $request)
    {
        /*
        |-----------------------------------------------------------------------
        | Task 3 Guest, step 5. You should implement this method as instructed
        |-----------------------------------------------------------------------
        */

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            return redirect()->to('/');
        }

        return redirect()->route('login');

    }

    public function register()
    {
        return view('register');
    }

    public function doRegister(Request $request)
    {
        /*
        |-----------------------------------------------------------------------
        | Task 2 Guest, step 5. You should implement this method as instructed
        |-----------------------------------------------------------------------
        */
        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $password = Hash::make($request->get('password'));
        if (Hash::check($request->get('password-confirmation'), $password)) {
            $user->password = $password;
            $user->save();
            auth()->login($user);
            return redirect()->route('home');
        } else
            return redirect()->route('register');


    }

    public function logout()
    {
        /*
        |-----------------------------------------------------------------------
        | Task 2 User, step 3. You should implement this method as instructed
        |-----------------------------------------------------------------------
        */
        auth()->logout();

        return redirect()->route('home');
    }
}
