<?php

namespace App\Http\Controllers;

use App\User;
use App\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use League\Flysystem\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Session\Session;
// use Session;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('login');
        $this->middleware('guest')->only('login');
    }


    public function index()
    {
        $user = Auth::user();
        $username = $user->username;
        $company = $user->pershID;
        if ($company == 963) {
            session()->put('company', 'CR');
            return redirect()->action('UserController@dashboard', $username);
        } else if ($company == 959) {
            session()->put('company', 'MFM');
            return redirect()->action('UserController@dashboard', $username);
        } else {
            return view('user.company', ['username' => $username]);
        }
        // $roles = $user->access()->orderby('access_id', 'asc')->where('url', 'NOT LIKE', 'surat/%')->get();
        // foreach ($roles as $role) {
        //     if ($role->name === "Daftar User") {
        //         return redirect()->action('UserController@list', $username);
        //     } else if ($role->name === "Daftar Surat") {
        //         return redirect()->action('LetterController@index', $username);
        //     }
        // }

    }

    public function company($user, $company)
    {
        session()->put('company', $company);
        return redirect()->action('UserController@dashboard', $user);
    }


    public function dashboard($user)
    {
        // $this->checkAccess();
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access()->orderby('access_id', 'asc')
            // ->where('url', 'NOT LIKE', 'surat/%')
            ->get();
        // $title = last(request()->segments());
        // $title = Access::where('url', $title)->first();
        // $title = $title->name;
        $title = "dashboard";
        $company = session()->get('company');
        return view('user.dashboard', compact('username', 'roles', 'title', 'company'));
    }

    public function edit($user, $name)
    {
        $user = \Auth::user();
        $username = $user->username;
        // $this->checkAccess();
        return response()->json(compact('name'), 200);
    }

    public function update($user, $name)
    {
        $this->validateUpdate();
        $user = \Auth::user();
        $username = $user->username;
        $users = User::where('username', $name)->first();
        $password = request('password');
        if (password_verify($password, $users->password)) {
            $newPassword = \Hash::make(request('password_confirmation'));
            $users->update(['password' => $newPassword]);
            $query = $this->getQueryString();
            return redirect()->action('UserController@list', [$username, $query])->with('message', 'User berhasil di edit');
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
                'password_confirmation' => 'required|min:6',
                'perusahaan' => 'required'
            ],
            [
                'password.required' => 'Harap masukkan password lama anda.',
                'password_confirmation.required' => 'Harap masukkan password baru anda.',
                'password_confirmation.min' => 'Minimal 6 karakter.'
            ]
        );
        return $data;
    }

    public function checkAccess()
    {
        $check = false;
        $user = request()->segment(1);
        $user = User::where('username', $user)->firstorfail();
        $roles = $user->access()->get();
        $url = request()->segment(2);
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

    public function updateAccess($user, $name)
    {
        $access = Input::all('access');
        $users = User::where('username', $name)->first();
        foreach ($access as $access) {
            $users->access()->sync($access);
        }
        $query = $this->getQueryString();
        return redirect()->action('UserController@list', [$user, $query])->with('message', 'Akses berhasil di edit');
    }

    public function logout()
    {
        \Auth::logout();
        session()->flush();
        return redirect('/');
    }

    public function list()
    {
        // $this->checkAccess();
        $user = \Auth::user();
        $username = $user->username;
        $filter = request('filter');
        $pagination = request('show_data') ? request('show_data') : 10;
        $sort_by = request()->get('sortby') ? request()->get('sortby') : 'id';
        $sort_type = request()->get('sorttype') ? request()->get('sorttype') : 'desc';
        $roles = $user->access()->orderby('access_id', 'asc')
            // ->where('url', 'NOT LIKE', 'surat/%')
            ->get();
        $q1 = DB::table('penomoran.users')
            ->orderby($sort_by, $sort_type)
            ->where('username', 'like', '%' . $filter . '%')
            ->select('*');
        $q2 = DB::table('user.users')
            ->orderby($sort_by, $sort_type)
            ->where('username', 'like', '%' . $filter . '%')
            ->select('*');
        // dd($q1);
        $users = $q2->union($q1)->paginate($pagination);
        // dd($users);
        // $users = User::orderby($sort_by, $sort_type)
        //     ->where('username', 'like', '%' . $filter . '%')
        //     ->paginate($pagination);
        $title = last(request()->segments());
        $title = Access::where('url', $title)->first();
        $title = $title->name;
        $count = $users->count();
        if (request()->ajax()) {
            $msg = ($count != 0) ? "" : "Data tidak ditemukan";
            return view('user.indexlist', compact('username', 'roles', 'users', 'title', 'msg'))->render();
        }
        $msg = ($count != 0) ? "" : "Belum ada data. Silahkan buat data baru";
        return view('user.index', compact('username', 'roles', 'users', 'title', 'msg'));
    }

    public function login()
    {
        // kalau database local ada usernya pakai yang local
        // kalau database local gk ada tapi dari database lain ada usernya
        // cek id, password dari database lain jika cocok login
        $this->validation();
        if ($this->checkData()) {
            $username = request('username');
            $users = User::where('username', $username)->first();
            \Auth::login($users, true);
            return redirect()->action('UserController@index', $username);
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
        $login = User::on('mysql')->where('username', $username)->first();
        if ($login != NULL) {
            Session()->put('dbname', 'mysql');
            return (password_verify($password, $login->password)) ? TRUE : FALSE;
        } else {
            $login = User::on('mysql2')->where('username', $username)->first();
            if ($login != NULL) {
                Session()->put('dbname', 'mysql2');
                return (password_verify($password, $login->password)) ? TRUE : FALSE;
            } else
                return FALSE;
        }
    }

    public function show($user, $name)
    {
        $data = User::where('username', $name)->first();
        return response()->json($data, 200);
    }

    public function create($user)
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access()->orderby('access_id', 'asc')->get();
        return response()->json();
    }

    public function store($user)
    {
        $user = \Auth::user();
        if ($this->validateRegister()) {
            $newuser = new User;
            $newuser->username = request('username');
            $newuser->password = \Hash::make(request('password'));
            $newuser->pershID = request('perusahaan');
            $newuser->save();
            $query = $this->getQueryString();
            return redirect()->action('UserController@list', [$user->username, $query])->with('message', 'User baru berhasil di buat');
        }
        return response()->json(["msg" => "gagal"], 402);
    }

    public function validateRegister()
    {
        $data = request()->validate(
            [
                'username' => 'required|unique:' . session()->get('dbname') . '.users',
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

    public function editAccess($user, $name)
    {
        // $this->checkAccess();
        $access = Access::all();
        $user = \Auth::user();
        $username = $user->username;
        // utk yg sedang login
        $roles = $user->access()->orderby('access_id', 'asc')->get();
        // acces utk di edit
        $useraccess = User::where('username', $name)->first();
        $useraccess = $useraccess->access()->get();
        $usergranted = [];
        foreach ($useraccess as $useraccess) {
            array_push($usergranted, $useraccess->id);
        }
        $count = count($usergranted);
        return response()->json(compact('access', 'roles', 'count', 'username',  'usergranted', 'name'), 200);
    }

    public function destroy($user, $name)
    {
        $user = \Auth::user();
        User::where('username', $name)->delete();
        $query = $this->getQueryString();
        return redirect()->action('UserController@list', [$user->username, $query])->with('message', 'User berhasil di hapus');
    }

    function getQueryString()
    {
        $page = '?page=' . request()->get('page');
        $query = '&filter=' . request()->get('filter');
        $sortby = '&sortby=' . request()->get('sortby');
        $sorttype = '&sorttype=' . request()->get('sorttype');
        $show_data = '&show_data=' . request()->get('show_data');
        return $page . $query . $sortby . $sorttype . $show_data;
    }
}
