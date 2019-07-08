@extends('layouts.layout')

@section('title', 'Dashboard')

@include('user.header')
@include('user.nav')

@section('content')
    <h1>Access for {{ $username }}</h1>
    <form>
        <table class="table">
            <tr>
                <td>Name</td>
                <td>Active</td>
            </tr>
            @foreach ($access as $access)
                <tr>
                    <td> {{ $access->name }}</td>
                    <td> <input type="checkbox" name="access" value="{{ $access->id }}"> </td>
                </tr>
            @endforeach
        </table>
        <div class="mx-auto d-block w-25">
            <button type="submit" name="save" class="btn btn-primary">Save</button>
            <a class="btn btn-primary" href="#" role="button">Back</a>
        </div>
    </form>

@endsection