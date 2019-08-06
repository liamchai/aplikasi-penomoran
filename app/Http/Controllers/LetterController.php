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

    public function checkPermission($surat, $user)
    {
        if ($surat->submitted_by != $user && $user != 'test') {
            abort(403);
        }
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

    public function show($user, $id)
    {
        $surat = Letter::where('id', $id)->firstOrFail();
        $surat->tanggal = date('d M Y', strtotime($surat->tanggal));
        $this->checkPermission($surat, $user);
        return response()->json($surat, 200);
    }

    public function index($user, $query = '')
    {
        $users = \Auth::user();
        $username = $users->username;
        $start_date = request('start_date');
        $end_date = request('end_date');
        $title = last(request()->segments());
        $title = Access::where('url', $title)->first();
        $title = $title->name;
        $filter = request('filter');
        $pagination = request('show_data') ? request('show_data') : 10;
        $sort_by = (request()->get('sortby')) ? request()->get('sortby') : 'id';
        $sort_type = (request()->get('sorttype')) ? request()->get('sorttype') : 'desc';
        $roles = $users->access()->orderby('access_id', 'asc')->where('url', 'NOT LIKE', 'surat/%')->get();
        $dropdown = $users->access()->orderby('access_id', 'asc')->where('url', 'LIKE', 'surat/%')->get();
        if ($user != "test") {
            $surats = Letter::where('submitted_by', $user)->orderby($sort_by, $sort_type);
        } else {
            $surats = Letter::orderby($sort_by, $sort_type);
        }
        if ($start_date != "" && $end_date != "")
            $surats = $surats->whereBetween('tanggal', array($start_date, $end_date));

        $surats = $surats->where(function ($query) use ($filter) {
            $query->where('nomor_surat', 'LIKE', '%' . $filter . '%')
                ->orWhere('name', 'LIKE', '%' . $filter . '%')
                ->orWhere('submitted_by', 'LIKE', '%' . $filter . '%')
                ->orWhere('penerima', 'LIKE', '%' . $filter . '%')
                ->orWhere('tanggal', 'LIKE', '%' . $filter . '%');
        })->orderby('id', 'desc')->paginate($pagination);

        $count = $surats->count();
        if (request()->ajax()) {
            $msg = ($count != 0) ? "" : "Data tidak ditemukan";
            return view('user.surat.listsurat', compact('username', 'roles', 'users', 'surats', 'user', 'msg'))->render();
        }
        $msg = ($count != 0) ? "" : "Belum ada data. Silahkan buat data baru";
        return view('user.surat.index', compact('surats', 'title', 'username', 'roles', 'user', 'dropdown', 'msg'));
    }

    public function list($user, $letter)
    {
        $user = \Auth::user();
        $username = $user->username;
        $url = $letter;
        $letter = "surat/" . $letter;
        $letter = Access::where('url', $letter)->first();
        $departemen = $letter->departemen;
        $title = $letter->name;
        $date =  date('d M Y', strtotime('now'));
        return response()->json(compact('username', 'date', 'letter', 'title', 'url', 'departemen'));
    }

    public function store($user)
    {
        $check = false;
        $user = request()->segment(1);
        $user = User::where('username', $user)->first();
        $penerima = request('penerima');
        $letter = request('url');
        $letter = "surat/" . $letter;
        $letter = Access::where('url', $letter)->first();
        $name = $letter->name;
        $departemen = $letter->departemen;
        $cekReset = Letter::where('name', $name)->orderby('id', 'desc')->first();
        if ($cekReset == "") {
            $cekReset = date('n', strtotime('now'));
        } else {
            $cekReset = date('n', strtotime($cekReset->tanggal));
        }
        $nomor = ++$letter->nomor;
        if ($cekReset != (int) date('n')) {
            $nomor = 1;
        }
        $no = $nomor;
        $month = $this->getRomawi(date('n'));
        $nomor = $this->checkDigit($nomor);
        $year = date('Y');
        $nomor_surat = $nomor . "/" . $departemen . "/" . $month . "/" . $year;
        $permissions = $user->access()->get();
        foreach ($permissions as $permission) {
            if ($permission->name == $letter->name) {
                $check = true;
                break;
            }
        }
        if ($check == false) {
            return response()->json(['message' => 'Terjadi Error Silahkan Refresh'], 403);
        }
        $newletter = new Letter;
        $newletter->tanggal = Carbon::now();
        $newletter->nomor_surat = $nomor_surat;
        $newletter->name = $name;
        $newletter->nomor = $no;
        $newletter->penerima = $penerima;
        $newletter->submitted_by = $user->username;
        $newletter->save();
        $letter->update(["nomor" => $no]);
        $query = $this->getQueryString();
        return redirect()->action('LetterController@index', [$user->username, $query])->with('message', 'Surat Baru berhasil di buat');
    }

    public function update($user, $id)
    {
        $query = $this->getQueryString();
        $letter = Letter::where('id', $id)->first();
        $penerima = request('penerima');
        $letter->update(['penerima' => $penerima]);
        return redirect()->action('LetterController@index', [$user, $query])->with('message', 'Surat berhasil di update');
    }

    public function edit($user, $id)
    {
        $surat = Letter::where('id', $id)->first();
        $surat->tanggal = date('d M Y', strtotime($surat->tanggal));
        $this->checkPermission($surat, $user);
        return response()->json($surat, 200);
    }

    public function destroy($user, $id)
    {
        Letter::where('id', $id)->delete();
        $query = $this->getQueryString();
        return redirect()->action('LetterController@index', [$user, $query])->with('message', 'Surat berhasil di hapus');;
    }

    public function getInfo($user, $letter)
    {
        $letter = "surat/" . $letter;
        $letter = Access::where('url', $letter)->first();
        $nomor = $letter->nomor;
        $data = Letter::where('nomor', $nomor)->latest()->first();
        $tanggal = date('d M Y', strtotime($data->tanggal));
        return response()->json([$data, $tanggal]);
    }

    function getQueryString()
    {
        $page = '?page=' . request()->get('page');
        $query = '&filter=' . request()->get('filter');
        $start_date = '&start_date=' . request()->get('start_date');
        $end_date = '&end_date=' . request()->get('end_date');
        $sortby = '&sortby=' . request()->get('sortby');
        $sorttype = '&sorttype=' . request()->get('sorttype');
        $show_data = '&show_data=' . request()->get('show_data');
        return $page . $query . $start_date . $end_date . $sortby . $sorttype . $show_data;
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
