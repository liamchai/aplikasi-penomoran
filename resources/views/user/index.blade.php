@extends('layouts.layout')

@section('title', $title)

@section('content')

@include('layouts.header')
@include('layouts.nav')

<h1>{{$title}}</h1>
{{-- {{dd($username)}} --}}
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">User Baru</button>

    <div class="alert alert-success mt-2 d-none" id="registermsg" role="alert">
        User Baru Berhasil di tambahkan
    </div>
    <div class="alert alert-success mt-2 d-none" id="updatemsg" role="alert">
        User berhasil di edit
    </div>
@if (count($users) > 0)
    <section class="users">
        @include('user.indexlist')
    </section>
@endif

@include('user.modal.registermodal')
@include('user.modal.updatemodal')
@include('user.modal.deletemodal')

<script>
$(document).ready(function () {
// pagination function start
    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');  
        getArticles(url);
        window.history.pushState("", "", url);
    });

    function getArticles(url) {
        $.ajax({
            url : url
        }).done(function (data) {
            $('.users').html(data);
        }).fail(function () {
            alert('Articles could not be loaded.');
        });
    }
// pagination function ends


});
</script>
@endsection
