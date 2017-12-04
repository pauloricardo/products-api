<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Requests\LoginRequest;
use App\Proxy\LoginProxy;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RequestAuthentication extends Controller
{
    private $loginProxy;

    public function __construct(LoginProxy $loginProxy)
    {
        $this->loginProxy = $loginProxy;
    }
    public function login(LoginRequest $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $accepted = $this->loginProxy->attemptLogin($email, $password);

        $user = new User();
        $user->email = $email;
        $user->password = $password;
        Auth::login($user);
        return response($this->loginProxy->attemptLogin($email, $password));
    }

    public function refresh(Request $request)
    {
        return response($this->loginProxy->attemptRefresh());
    }

    public function logout()
    {
        $this->loginProxy->logout();
        Auth::logout();
        return response(null, 204);
    }
}
