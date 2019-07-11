<?php

namespace App\Http\Controllers;

use App\User;
use App\Letter;
use Carbon\Carbon;
use App\Access;
use Illuminate\Http\Request;

class LetterController extends Controller
{
    public function __construct()
    {
        $check = false;
        $user = request()->segment(1);
        $user = User::where('username', $user)->firstorfail();
        $roles = $user->access()->get();
        $url = "surat/" . last(request()->segments());
        foreach ($roles as $roles) {
            if ($url == $roles->url) {
                $check = true;
                break;
            }
        }
        if ($check == false) {
            return abort(403);
        }
        $this->middleware('auth');
    }


    public function index($user)
    {
        $users = \Auth::user();
        $username = $users->username;
        $roles = $users->access()->orderby('access_id', 'asc')->get();
        if ($user != "test") {
            $surats = Letter::where('submitted_by', $user)->orderby('id', 'desc')->paginate(10);
        } else {
            $surats = Letter::orderby('id', 'desc')->paginate(10);
        }
        return view('user.surat.listsurat', compact('surats', 'username', 'roles', 'user'));
    }

    public function list($user, $letter)
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access()->orderby('access_id', 'asc')->get();
        $url = $letter;
        $letter = "surat/" . $letter;
        $letter = Access::where('url', $letter)->first();
        $month = $this->getRomawi(date('n'));
        $no = $this->checkDigit(++$letter->nomor);
        $departemen = $letter->departemen;
        $title = $letter->name;
        return view('user.surat', compact('username', 'roles', 'letter', 'month', 'no', 'title', 'url', 'departemen'));
    }

    public function store($user, $letter)
    {
        $test = request('nomor') . "/" . request('departemen') . "/" . request('month') . "/" . request('year');
        $nomor = (int) ltrim(request('nomor'), "00");
        $letter = "surat/" . $letter;
        $letter = Access::where('url', $letter)->first();
        $newletter = new Letter;
        $newletter->tanggal = Carbon::now();
        $newletter->nomor_surat = $test;
        $newletter->name = $letter->name;
        $newletter->nomor = $nomor;
        $newletter->submitted_by = $user;
        $newletter->save();
        $letter->update(["nomor" => $nomor]);
        return redirect()->action('LetterController@index', $user)->with('msg', 'Surat berhasil di tambahkan');
    }

    function getRomawi($bln)
    {
        switch ($bln) {
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }

    function checkDigit($no)
    {
        $len = strlen($no);
        switch ($len) {
            case 1:
                return "00" . $no;
                break;
            case 2:
                return "0" . $no;
                break;
            case 3:
                return $no;
                break;
        }
    }
}
