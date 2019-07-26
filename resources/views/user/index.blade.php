@extends('layouts.layout')

@section('title', $title)

@section('content')

@include('layouts.header')
@include('layouts.nav')

<h1 class="display-4">{{$title}}</h1>
<div class="row">
    <div class="col-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">User Baru</button>
    </div>
    <div class="col-5">
    </div>
    <div class="col-4">
        <input type="text" name="filter" id="filter" class="d-block pl-2" placeholder="Cari Data">
        <input type="hidden" name="page_hidden" id="page_hidden">
        <input type="hidden" name="url_hidden" id="url_hidden">
    </div>
</div>
    <div class="alert alert-success mt-2 d-none" id="registermsg" role="alert">
        User Baru Berhasil di tambahkan
    </div>
    <div class="alert alert-success mt-2 d-none" id="updatemsg" role="alert">
        User berhasil di edit
    </div>
    <div class="alert alert-success mt-2 d-none" id="deletemsg" role="alert">
        User Berhasil di hapus
    </div>
    <div class="alert alert-success mt-2 d-none" id="accessmsg" role="alert">
        Akses User Berhasil di update
    </div>
@if (count($users) > 0)
    <section class="users">
        @include('user.indexlist')
    </section>  
@endif

@include('user.modal.user.registermodal')
@include('user.modal.user.updatemodal')
@include('user.modal.user.deletemodal')
@include('user.modal.user.edituseraccessmodal')

<script>
$(document).ready(function () {
// pagination function start
    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href').split('page=')[0];
        var url = url.replace('?', '');
        $('#url_hidden').val(url);
        var page = $(this).attr('href').split('page=')[1];
        $('#page_hidden').val(page);
        var query = $('#filter').val();  
        fetch_data(url, page, query);
        window.history.pushState("", "", url);
    });
// pagination function ends

//search code start
    $('#filter').on('keyup',function(e) {
        $('#page_hidden').val('');
        var query = $('#filter').val();
        var page = "";
        var url = $('#url_hidden').val();
        fetch_data(url, page, query);
// end search
    });

    function fetch_data(url, page, query='') {
        $.ajax({
            url : url + '?page=' + page + '&filter=' + query
        }).done(function (data) {
            $('.users').html(data);
            console.log(data);
        }).fail(function () {
            alert('Data Tidak Ditemukan');
        });
    }
});
</script>
@endsection
