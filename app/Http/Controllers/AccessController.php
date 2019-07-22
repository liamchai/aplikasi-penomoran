<?php

namespace App\Http\Controllers;

use App\User;
use App\Access;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function index()
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
}
