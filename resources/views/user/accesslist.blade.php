@extends('layouts.layout')

@section('title', 'Dashboard')

@include('user.header')
@include('user.nav')

@section('content')
    <table class="table">
        <h1>Access List</h1>
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