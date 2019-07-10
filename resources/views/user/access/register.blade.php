@extends('layouts.layout')

@section('title')

@include('layouts.header')
@include('layouts.nav')
@section('content')
<h1 class="text-center">Register New Access</h1>
<form role="form" class="w-50 mx-auto" id="newModalForm" method="POST" action={{ action('UserController@accessliststore', $username) }}>
    <div class="form-group">
            <label for="name">Access Name : </label>
            <input autocomplete="off" type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @error('name')
                <span class="text-danger pl-2">{{ $message }}</span>
            @enderror
    </div>
    <div class="form-group">
            <label for="URL">URL : </label>
            <input autocomplete="off" type="text" class="form-control" id="URL" name="URL" value="{{ old('URL') }}">
            @error('URL')
                <span class="text-danger pl-2">{{ $message }}</span>
            @enderror
    </div>
    <div class="form-group">
        <label for="departemen">Departemen : </label>
        <input autocomplete="off" type="text" class="form-control" id="departemen" name="departemen" value="{{ old('departemen') }}">
        @error('departemen')
            <span class="text-danger pl-2">{{ $message }}</span>
        @enderror
</div>
    <div class="modal-footer mx-auto">
        <button type="submit" class="btn btn-primary">Register</button>
        <a class="btn btn-primary" href={{ action('UserController@accesslist', $username) }} role="button">Back</a>
        @csrf
</form> 
@endsection
