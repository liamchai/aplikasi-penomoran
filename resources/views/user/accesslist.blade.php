@extends('layouts.layout')

@section('title', 'Dashboard')

@include('user.header')
@include('user.nav')

@section('content')
<h1>Access List</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Register New Access</button>
<table class="table mt-2">
        <tr>
            <td>Name</td>
            <td>URL</td>
        </tr>
        @foreach ($access as $access)
        <tr>
            <td>{{$access->name}}</td>
            <td>{{$access->url}}</td>
        </tr>
        @endforeach
@endsection