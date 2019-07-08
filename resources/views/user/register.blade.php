@extends('layouts.layout')

@section('title', 'Dashboard')

@include('user.header')
@include('user.nav')

@section('content')
    <h1 class="text-center">Register</h1>
    <form role="form" class="w-50 mx-auto" id="newModalForm" method="POST" action={{ action('UserController@store', $username) }}>
        <div class="form-group">
                <label for="username">Username : </label>
                <input type="text" class="form-control" id="username" name="username" value={{ old('username') }}>
                @error('username')
                    <span class="text-danger pl-2">{{ $message }}</span>
                @enderror
        </div>
        <div class="form-group">
                <label for="password">Password : </label>
                <input type="password" class="form-control" id="password" name="password">
                @error('password')
                    <span class="text-danger pl-2">{{ $message }}</span>
                @enderror
        </div>
        <div class="form-group">
                <label for="password_confirmation">Confirm Password : </label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                @error('password_confirmation')
                    <span class="text-danger pl-2">{{ $message }}</span>
                @enderror
        </div>
        <div class="modal-footer mx-auto">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
                @csrf
</form> 
@endsection