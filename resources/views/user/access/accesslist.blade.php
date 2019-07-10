@extends('layouts.layout')

@section('title', $title)

@include('layouts.header')
@include('layouts.nav')

@section('content')
<h1>{{$title}}</h1>
    {{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Register New Access</button> --}}
    <a href={{ action('UserController@accesslistregister', $username) }} class="btn btn-primary">Register New Access</a>
    @if (session('msg'))
    <div class="alert alert-success mt-2" role="alert">
        {{ session('msg') }}
    </div>
@endif
<table class="table mt-2">
        <tr>
            <td>Name</td>
            <td>URL</td>
        </tr>
        @foreach ($access as $acc)
        <tr>
            <td>{{$acc->name}}</td>
            <td>{{$acc->url}}</td>
        </tr>
        @endforeach
        <tr>
        <td colspan="2">{{ $access->links() }}</td>
        </tr>
@endsection