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
        foreach ($roles as $role) {
            if ($role->name === "Daftar User") {
                return redirect()->action('UserController@show', $user->username);
            } else if ($role->name === "List Surat") {
                return redirect()->action('LetterController@index', $user->username);
            }
        }
    }

    public function edit($user, $name)
    {
        $user = \Auth::user();
        $username = $user->username;
        return response()->json(compact('name'), 200);
    }

    public function update($user, $name)
    {
        $this->validateUpdate();
        $user = \Auth::user();
        $users = User::where('username', $name)->first();
        $password = request('password');
        if (password_verify($password, $users->password)) {
            $newPassword = \Hash::make(request('password_confirmation'));
            $users->update(['password' => $newPassword]);
            return response()->json([$users], 200);
        } else {
            return response()->json(['msg' => 'Password lama anda tidak sesuai'], 401);
        }
    }

    public function validateUpdate()
    {
        $data = request()->validate(
            [
                'username' => 'required',
                'password' => 'required',
                'password_confirmation' => 'required|min:6'
            ],
            [
                'password.required' => 'Harap masukkan password lama anda.',
                'password_confirmation.required' => 'Harap masukkan password baru anda.',
                'password_confirmation.min' => 'Minimal 6 karakter.'
            ]
        );
        return $data;
    }


    public function updateAccess($user, $name)
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
        $users = User::orderby('id', 'desc')->paginate(10);
        $title = last(request()->segments());
        $title = Access::where('url', $title)->first();
        $title = $title->name;
        if (request()->ajax()) {
            return view('user.indexlist', compact('username', 'roles', 'users', 'title'))->render();
        }
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
            return response()->json(['message' => 'Username atau password anda salah'], $status = 401);
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
        // return response()->json(['message' => $data, $status = 422]);
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
        return response()->json(["msg" => "gagal"], 402);
    }

    public function validateRegister()
    {
        $data = request()->validate(
            [
                'username' => 'required|unique:users',
                'password' => 'required|min:6|same:password_confirmation',
                'password_confirmation' => 'same:password|required'
            ],
            [
                'username.required' => 'Harap masukkan username anda.',
                'username.unique' => 'Username sudah digunakan',
                'password.required' => 'Harap masukkan password anda.',
                'password.min' => 'Minimal 6 karakter',
                'password.same' => 'Password harus sama dengan password dibawah',
                'password_confirmation.required' => 'Harap masukkan ulangi password.',
                'password_confirmation.same' => 'Password harus sama dengan password diatas'
            ]
        );
        return $data;
    }

    public function editAcess($user, $name)
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
        return response()->json(['msg' => "success"], 200);
        // return view('user.access.access', compact('access', 'roles', 'username', 'name', 'usergranted', 'count'));
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

    public function destroy($user, $name)
    {
        $user = \Auth::user();
        User::where('username', $name)->delete();
        // return response()->json();
        return redirect()->action('UserController@show', $user->username);
    }
}
