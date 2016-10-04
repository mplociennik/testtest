<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;

class LoginController extends Controller {

    public function googleRedirect() {
        return Socialite::with('google')->redirect();
    }

    public function googleLogin() {
        $user =  Socialite::with('google')->user();
        var_dump($user); die;
    }

}
