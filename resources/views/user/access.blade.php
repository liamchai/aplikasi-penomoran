@extends('layouts.layout')

@section('title', 'Dashboard')

@include('user.header')
@include('user.nav')

@section('content')
    <h1>Access for {{ $name }}</h1>
    <form method="POST" action={{ action('UserController@update', [$username, $name]) }}>
        @csrf
        @method('PATCH')
        <table class="table">
            <tr>
                <td>Name</td>
                <td>Active</td>
            </tr>
            @foreach ($access as $access)
                <tr>
                    <td>{{ $access->name }}</td>
                    <td><input type="checkbox" name="access[]" value="{{ $access->id }}"
                        @for ($i = 0; $i < $count; $i++)
                            @if ($usergranted[$i] == $access->id)
                                {{ "checked" }}
                            @endif
                        @endfor></td>
                </tr>
            @endforeach
        </table>
        <div class="mx-auto d-block w-25">
            <button type="submit" name="save" class="btn btn-primary">Save</button>
            <a class="btn btn-primary" href={{ action('UserController@show', $username) }} role="button">Back</a>
        </div>
    </form>

@endsection