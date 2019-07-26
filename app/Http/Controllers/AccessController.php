<?php

namespace App\Http\Controllers;

use App\User;
use App\Access;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($user, $query = '')
    {
        $this->checkAccess();
        $user = \Auth::user();
        $username = $user->username;
        $filter = request('filter');
        $roles = $user->access()->orderby('access_id', 'asc')->where('url', 'NOT LIKE', 'surat/%')->get();
        $title = last(request()->segments());
        $title = Access::where('url', $title)->first();
        $title = $title->name;
        $access = Access::where('name', 'LIKE', '%' . $filter . '%')->orWhere('URL', 'LIKE', '%' . $filter . '%')->orderby('id', 'desc')->paginate(10);

        $count = $access->count();
        if (request()->ajax()) {
            $msg = ($count != 0) ? "" : "Data tidak ditemukan";
            return view('user.access.accesslist', compact('username', 'roles', 'access', 'title', 'msg'))->render();
        }
        $msg = ($count != 0) ? "" : "Belum ada data. Silahkan buat data baru";
        return view('user.access.index', compact('username', 'roles', 'access', 'title', 'msg'));
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

    public function store($user)
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
            return redirect()->action('AccessController@index', $username)->with('msg', 'Access baru berhasil di tambahkan');
        } else {
            return response()->json(['msg' => 'Terjadi Kesalahan silahkan contact admin'], 401);
        }
    }

    public function validateAccess()
    {
        $id = request('id');
        $data = request()->validate(
            [
                'name' => ['required', Rule::unique('accesses')->ignore($id)],
                'URL' => ['required', Rule::unique('accesses')->ignore($id)]
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

    public function show($user, $id)
    {
        $access = Access::where('id', $id)->firstOrFail();
        return response()->json($access, 200);
    }

    public function edit($user, $id)
    {
        $access = Access::where('id', $id)->firstOrFail();
        return response()->json($access, 200);
    }

    public function update($user, $id)
    {
        $this->validateAccess();
        $access = Access::where('id', $id)->first();
        $access->name = request('name');
        $access->url = request('URL');
        $access->departemen = request('departemen');
        $access->Save();
        $query = $this->getQueryString();
        return redirect()->action('AccessController@index', [$user, $query]);
        // return response()->json($access, 200);
    }

    public function destroy($user, $id)
    {
        Access::where('id', $id)->delete();
        $query = $this->getQueryString();
        return redirect()->action('AccessController@index', [$user, $query]);
    }

    function getQueryString()
    {
        $page = '?page=' . request()->get('page');
        $query = '&filter=' . request()->get('query');
        return $page . $query;
    }
}
