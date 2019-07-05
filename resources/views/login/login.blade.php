@extends('layouts.layout')

@section('content')
<body>
    <div class="container w-25 mt-5">
        <h1 class="text-center">Login</h1>
        <form method="POST" action="{{ action('UserController@login') }}">
            <div class="form-group">
                <label for="username">Username : </label>
                <input type="text" class="form-control" id="username" name="username" value={{ old('username') }}>
                @error('username')
                    <span class="text-danger pl-2">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password">
                @error('password')
                    <span class="text-danger pl-2">{{ $message }}</span>
                @enderror
            </div>
            <div class="checkbox">
                <label><input type="checkbox"> Remember me</label>
                @error('msg')
                    <div class="text-danger pl-2 mb-2">{{ $message }}</div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            @csrf
        </form>
    </div>
@endsection