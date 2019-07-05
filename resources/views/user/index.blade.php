@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
    {{ (session('username')) }}
@endsection