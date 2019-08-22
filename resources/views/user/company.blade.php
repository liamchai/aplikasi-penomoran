@extends('layouts.layout')

@section('title', 'Pilih Perusahaan')

@section('content')
<div class="container">
    <div class="row d-flex justify-content-center">
        <h1 class="display-4">Silahkan Pilih Perusahaan</h1>
    </div>
    <div class="row">
        <div class="col-2">
        </div>
        <div class="col-3">
            {{-- <a href={{action('UserController@dashboard', $username)}}> --}}
            <form method="POST" action={{action('UserController@company', [$username, 'MFM'])}}>
                @csrf
                <button type="submit" class="m-0 p-0 border border-none">
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src={{asset('img/medanfm.png')}} alt="Card image cap">
                </div>
                </button>
            </form>
        </div>
        <div class="col-1">
        </div>
        <div class="col-6">
            <form method="POST" action={{action('UserController@company', [$username, 'CR'])}}>
                @csrf
                <button type="submit" class="m-0 p-0 border border-none">
                <div class="card" style="width: 18rem;">
                        <img class="card-img-top" src="{{asset('img/cityradio.jpg')}}" alt="Card image cap">
                </div>
                </button>
            </form>
        </div>
    </div>
</div>