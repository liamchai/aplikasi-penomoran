@extends('layouts.layout')

@section('title', $title)

@section('content')

@include('layouts.header')
@include('layouts.nav')

    <h1 class="display-4">Selamat Datang Kembali, {{$username}}</h1>
    <p>{{$company}}</p>
@endsection
