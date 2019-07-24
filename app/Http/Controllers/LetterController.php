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
        $this->middleware('auth');
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

    public function show($user, $id)
    {
        $surat = Letter::where('id', $id)->firstOrFail();
        $surat->tanggal = date('d M Y', strtotime($surat->tanggal));
        if ($surat->submitted_by != $user && $user != 'test') {
            abort(403);
        }
        return response()->json($surat, 200);
    }

    public function index($user)
    {
        $users = \Auth::user();
        $username = $users->username;
        $filter = request('filter');
        $roles = $users->access()->orderby('access_id', 'asc')->where('url', 'NOT LIKE', 'surat/%')->get();
        $dropdown = $users->access()->orderby('access_id', 'asc')->where('url', 'LIKE', 'surat/%')->get();
        if ($user != "test") {
            $surats = Letter::where('submitted_by', $user)->orderby('id', 'desc');
        } else {
            $surats = Letter::orderby('id', 'desc');
        }
        $surats = $surats->where(function ($query) use ($filter) {
            $query->where('nomor_surat', 'LIKE', '%' . $filter . '%')
                ->orWhere('name', 'LIKE', '%' . $filter . '%')
                ->orWhere('submitted_by', 'LIKE', '%' . $filter . '%')
                ->orWhere('tanggal', 'LIKE', '%' . $filter . '%');
        })->orderby('id', 'desc')->paginate(10);

        if (request()->ajax()) {
            return view('user.surat.listsurat', compact('username', 'roles', 'users', 'surats', 'user'))->render();
        }
        return view('user.surat.index', compact('surats', 'username', 'roles', 'user', 'dropdown'));
    }

    public function list($user, $letter)
    {
        $user = \Auth::user();
        $username = $user->username;
        $url = $letter;
        $letter = "surat/" . $letter;
        $letter = Access::where('url', $letter)->first();
        $month = $this->getRomawi(date('n'));
        $no = $this->checkDigit(++$letter->nomor);
        $year = date('Y');
        $date =  date('d M Y', strtotime('now'));
        $departemen = $letter->departemen;
        $title = $letter->name;
        return response()->json(compact('username', 'date', 'year', 'letter', 'month', 'no', 'title', 'url', 'departemen'));
    }

    public function store($user)
    {
        $test = request('nomor_surat');
        $penerima = request('penerima');
        $nomor = (int) ltrim(request('nomor'), "00");
        $letter = request('url');
        $letter = "surat/" . $letter;
        $letter = Access::where('url', $letter)->first();
        $newletter = new Letter;
        $newletter->tanggal = Carbon::now();
        $newletter->nomor_surat = $test;
        $newletter->name = $letter->name;
        $newletter->nomor = $nomor;
        $newletter->penerima = $penerima;
        $newletter->submitted_by = $user;
        $newletter->save();
        $letter->update(["nomor" => $nomor]);
        return redirect()->action('LetterController@index', $user);
    }

    public function update($user, $id)
    {
        $letter = Letter::where('id', $id)->first();
        $penerima = request('penerima');
        $letter->update(['penerima' => $penerima]);
        return redirect()->action('LetterController@index', $user);
    }

    public function edit($user, $id)
    {
        $letter = Letter::where('id', $id)->first();
        $letter->tanggal = date('d M Y', strtotime($letter->tanggal));
        return response()->json($letter, 200);
    }

    public function destroy($user, $id)
    {
        Letter::where('id', $id)->delete();
        return redirect()->action('LetterController@index', $user);
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
