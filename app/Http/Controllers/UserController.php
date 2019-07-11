<?php

namespace App\Http\Controllers;

use App\User;
use App\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('login');
    }

    public function checkAccess()
    {
        $check = false;
        $user = request()->segment(1);
        $user = User::where('username', $user)->firstorfail();
        $roles = $user->access()->get();
        $url = last(request()->segments());
        foreach ($roles as $roles) {
            if ($url == $roles->url) {
                $check = true;
                break;
            }
        }
        if ($check == false) {
            return abort(403);
        }
    }

    public function index()
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access()->orderby('access_id', 'asc')->get();
        return view('user.home', compact('username', 'roles'));
    }

    public function update($user, $name)
    {
        $access = Input::all('access');
        $users = User::where('username', $name)->first();
        foreach ($access as $access) {
            $users->access()->sync($access);
        }
        return redirect()->route('admin', $user)->with('msg', 'User Access is Updated');
    }

    public function logout()
    {
        \Auth::logout();
        return redirect('/');
    }

    public function show($user)
    {
        $this->checkAccess();
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access()->orderby('access_id', 'asc')->get();
        $users = User::paginate(10);
        $title = last(request()->segments());
        $title = Access::where('url', $title)->first();
        $title = $title->name;
        return view('user.index', compact('username', 'roles', 'users', 'title'));
    }

    public function login()
    {
        $this->validation();
        if ($this->checkData()) {
            $username = request('username');
            $user = User::where('username', $username)->firstorFail();
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
        // dd(\Hash::make($password));
        $login = User::where('username', $username)->first();
        if ($login != NULL)
            return (password_verify($password, $login->password)) ? TRUE : FALSE;
        else
            return FALSE;
    }

    public function register($user)
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access()->orderby('access_id', 'asc')->get();
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
            return redirect()->action('UserController@show', $user->username)->with('msg', 'User Registered Successfully');
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
                'password.min' => 'Minimal 6 karakter',
                'password.same' => 'Silahkan ulangi password anda',
                'password_confirmation.same' => 'Password harus sama dengan password diatas'
            ]
        );
        return $data;
    }
    public function edit($user, $name)
    {
        $access = Access::all();
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access()->orderby('access_id', 'asc')->get();
        $useraccess = User::where('username', $name)->first();
        $useraccess = $useraccess->access()->get();
        $usergranted = [];
        foreach ($useraccess as $useraccess) {
            array_push($usergranted, $useraccess->id);
        }
        $count = count($usergranted);
        return view('user.access.access', compact('access', 'roles', 'username', 'name', 'usergranted', 'count'));
    }

    public function accesslist()
    {
        $this->checkAccess();
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access()->orderby('access_id', 'asc')->get();
        $title = (last(request()->segments()));
        $title = Access::where('url', $title)->first();
        $title = $title->name;
        $access = Access::paginate(10);
        return view('user.access.accesslist', compact('username', 'roles', 'access', 'title'));
    }

    public function accesslistregister()
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access()->orderby('access_id', 'asc')->get();
        return view('user.access.register', compact('username', 'roles'));
    }

    public function accessliststore()
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access;
        if ($this->validateAccess()) {
            $newaccess = new Access;
            $newaccess->name = request('name');
            $newaccess->url = request('URL');
            $newaccess->departemen = request('departemen');
            $newaccess->save();
            return redirect()->action('UserController@accesslist', $username)->with('msg', 'Access baru berhasil di tambahkan');
        } else {
            return redirect()->back()->withInput();
        }
    }

    public function validateAccess()
    {
        $data = request()->validate(
            [
                'name' => 'required|unique:accesses',
                'URL' => 'required|unique:accesses'
            ],
            [
                'name.required' => 'Access harus di isi',
                'URL.required' => 'URL harus di isi',
                'name.unique' => 'Access sudah terdaftar',
                'URL.unique' => 'URL sudah terdaftar'
            ]
        );

        return $data;
    }
}
