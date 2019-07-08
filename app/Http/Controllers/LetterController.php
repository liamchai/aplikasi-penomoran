<?php

namespace App\Http\Controllers;

use App\User;
use App\Letter;
use Illuminate\Http\Request;

class LetterController extends Controller
{
    public function index()
    {
        $user = \Auth::user();
        $username = $user->username;
        $roles = $user->access;
        $letter = Letter::find(1);
        $month = $this->getRomawi(date('n'));
        // $users = User::all();
        return view('user.surat', compact('username', 'roles', 'letter', 'month'));
    }

    public function store()
    { }

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
}
