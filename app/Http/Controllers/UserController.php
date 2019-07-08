<?php

namespace App\Http\Controllers;

use App\User;
use App\Access;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('login');
    }

    public function show($user, $name)
    {
        $access = Access::all();
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access;
        $name = $name;
        return view('user.access', compact('access', 'roles', 'username', 'name'));
    }

    public function logout()
    {
        \Auth::logout();
        return redirect('/');
    }

    public function index($user)
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access;
        $users = User::all();
        return view('user.index', compact('username', 'roles', 'users'));
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
        $password = request('password');

        $login = User::where('username', $username)->first();
        return (password_verify($password, $login->password)) ? TRUE : FALSE;
    }

    public function register($user)
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access;
        return view('user.register', compact('username', 'roles'));
    }

    public function store($user)
    {
        $user = \Auth::user();
        if ($this->validateRegister()) {
            $newuser = new User;
            $newuser->username = request('username');
            $newuser->password = \Hash::make(request('password'));
            $newuser->save();
            // $roles = Access::all();
            // $newuser->access()->sync($roles);
            return redirect()->action('UserController@index', $user->username)->with('msg', 'User Registered Successfully');
        }
    }

    public function validateRegister()
    {
        $data = request()->validate(
            [
                'username' => 'required|unique:users',
                'password' => 'required|min:6|same:password_confirmation',
                'password_confirmation' => 'same:password'
            ],
            [
                'username.required' => 'Harap masukkan username anda.',
                'username.unique' => 'Username sudah digunakan',
                'password.required' => 'Harap masukkan password anda.',
                'password.same' => 'Silahkan ulangi password anda'
            ]
        );

        return $data;
    }
}
