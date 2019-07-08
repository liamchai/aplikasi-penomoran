@extends('layouts.layout')

@section('title', 'Dashboard')

@include('user.header')
@include('user.nav')

@section('content')
    <table>
        <tr>
            <td>No </td>
            <td>:</td>
            <td>{{ ++$letter->number }}/HRD/{{$month}}/{{ date('Y') }}</td>
        </tr>
        <tr>
            <td>Kepada </td>
            <td>:</td>
            <td><input type="text" name="penerima"></td>
        </tr>
        <tr>
            <td>Keterangan </td>
            <td>:</td>
            <td><input type="text" name="keterangan"></td>
        </tr>
        <tr>
            <td>Dikeluarkan oleh</td>
            <td>:</td>
            <td>{{ $username }}</td>
        </tr>
        <tr>
            <td><button class="btn btn-primary" type="submit">Simpan</button></td>
        </tr> 
    </table>
@endsection