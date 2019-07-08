@extends('layouts.layout')

@section('title', 'Dashboard')

@include('user.header')
@include('user.nav')

@section('content')
<h1>User List</h1>
{{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Register New User</button> --}}
<a><a href={{ action('UserController@register', $username) }} class="btn btn-primary">Register New User</a></a>
@if (session('msg'))
    <div class="alert alert-success mt-2" role="alert">
        {{ session('msg') }}
    </div>
@endif
<table class="table mt-2">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Username</th>
            <th scope="col">Access</th>
            {{-- <th scope="col">Handle</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }} </td>
                <td>{{ $user->username }} </td>
                <td><a href="{{"/". $username ."/" . $user->username}}" >Show/Edit</td>
            </td>
        @endforeach
    </tbody>
</div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Register</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form role="form" id="newModalForm" method="POST" action={{ action('UserController@register', $username) }}>
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
                </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
            @csrf
        </form>
        </div>
        </div>
    </div>

{{-- <script>
    $("#exampleModal").submit(function(e) {
        e.preventDefault();
        var name = $("#username").val();
        var name = $("#password").val();
        var name = $("#password_confirmation").val();
        $("exampleModal")
    });

</script> --}}
@endsection