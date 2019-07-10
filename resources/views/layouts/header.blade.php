@extends('layouts.layout')

<div class="row bg-dark text-white">
    <header class="col-10">
        <h1 class="float-right">Welcome, {{ $username }} <a href="{{ route('logout') }}">Logout</a></h1>
    </header>
</div>