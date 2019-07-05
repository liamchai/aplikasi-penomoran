<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('login');
    }

    public function index($username)
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access;
        foreach ($roles as $role) {
            echo $role->name;
        }
    }

    public function login()
    {
        $this->validation();
        if ($this->checkData()) {
            $username = request('username');
            $user = User::where('username', $username)->first();
            \Auth::login($user, true);
            return redirect()->action('UserController@index', $user->username);
        } else {
            return redirect()->back()->withInput(request()->only('username'))->withErrors(['msg' => 'Username atau Password anda salah']);
        }
    }

    public function validation()
    {
        $data = request()->validate(
            [
                'username' => 'required',
                'password' => 'required'
            ],
            [
                'username.required' => 'Harap masukkan username anda.',
                'password.required' => 'Harap masukkan password anda.',
            ]
        );

        return $data;
    }

    public function checkData()
    {
        $username = request('username');
        // $password = password_hash(request('password'), PASSWORD_DEFAULT);
        $password = request('password');

        $login = User::where('username', $username)->where('password', $password)->first();
        // dd($login);
        return ($login != NULL) ? TRUE : FALSE;
    }
}
