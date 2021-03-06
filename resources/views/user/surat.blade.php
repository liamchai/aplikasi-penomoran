@extends('layouts.layout')

@section('title', $title)

@include('layouts.header')
@include('layouts.nav')

@section('content')
    <h1>{{$title}}</h1>
    <form action={{ action('LetterController@store', [$username, $url]) }} method="POST">
        @csrf
        <table>
            <tr>
                <td>No </td>
                <td>:</td>
                <td>&nbsp; {{ $no }} / {{ $departemen }} / {{$month}} / {{ date('Y') }}</td>
            </tr>
            <input type="hidden" name="nomor" value={{$no}}>
            <input type="hidden" name="departemen" value={{$departemen}}>
            <input type="hidden" name="month" value={{$month}}>
            <input type="hidden" name="year" value={{date('Y')}}>
            {{-- <input type="hidden" name="date" value={{date('d-M-Y')}}> --}}
            <tr>
                <td>Kepada </td>
                <td>:</td>
                <td>&nbsp;<input type="text" name="penerima"></td>
            </tr>
            <tr>
                <td>Keterangan </td>
                <td>:</td>
                <td>&nbsp;<input type="text" name="keterangan"></td>
            </tr>
            <tr>
                <td>Dikeluarkan oleh </td>
                <td>:</td>
                <td>&nbsp;{{ $username }}</td>
            </tr>
            <tr>
                <td><button class="btn btn-primary" type="submit">Simpan</button>
                    <a class="btn btn-primary" href={{ action('LetterController@index', $username) }} role="button">Kembali</a></td>
            </tr> 
        </table>
    </form>
@endsection