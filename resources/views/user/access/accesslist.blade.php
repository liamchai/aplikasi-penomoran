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
<table class="table mt-2 table-striped">
    <thead class="thead-light">
        <tr>
            <th scope="col">No</th>
            <th scope="col">Name</th>
            <th scope="col">URL</th>
        </tr>
    </thead>
    @php
    $i = ($access->currentPage()-1) * $access->perPage()+1 
    @endphp
        @foreach ($access as $acc)
        <tr>
            <td scope="row">{{$i++}}</td>
            <td>{{$acc->name}}</td>
            <td>{{$acc->url}}</td>
        </tr>
        @endforeach
    </table>
        <div>{{ $access->links() }}</div>
@endsection