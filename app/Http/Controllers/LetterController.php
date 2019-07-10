<?php

namespace App\Http\Controllers;

use App\User;
use App\Letter;
use App\Access;
use Illuminate\Http\Request;

class LetterController extends Controller
{
    public function index($user, $letter)
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access;
        $url = $letter;
        $letter = "surat/" . $letter;
        $letter = Access::where('url', $letter)->first();
        $month = $this->getRomawi(date('n'));
        $no = $this->checkDigit(++$letter->nomor);
        $title = $letter->name;
        return view('user.surat', compact('username', 'roles', 'letter', 'month', 'no', 'title', 'url'));
    }

    public function store($user, $letter)
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access;
        $test = request('nomor') . "/" . request('month') . "/" . request('year');
        $nomor = (int) ltrim(request('nomor'), "00");
        $url = $letter;
        $letter = "surat/" . $letter;
        $letter = Access::where('url', $letter)->first();
        $title = $letter->name;
        $newletter = new Letter;
        $newletter->name = $letter->name;
        $newletter->nomor = $nomor;
        $newletter->submitted_by = $username;
        $newletter->save();
        $letter->update(["nomor" => $nomor]);
        // return view('user.surat', compact('user', 'letter', 'title', 'username', 'roles'));
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
