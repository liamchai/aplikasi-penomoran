@extends('layouts.layout')

@section('title', $title)

@section('content')

@include('layouts.header')
@include('layouts.nav')

<h1 class="display-4">{{$title}}</h1>
<div class="row">
    <div class="col-3">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#RegisterAccessModal">Akses Baru</button>
    </div>
    {{-- <a href={{ action('UserController@accesslistregister', $username) }} class="btn btn-primary">Register New Access</a> --}}
    <div class="col-5">
        </div>
    <div class="col-4">
    <input type="hidden" name="page_hidden" id="page_hidden">
    <input type="hidden" name="url_hidden" id="url_hidden">
    <input type="text" name="filter" id="filter" class="pl-2" placeholder="Cari Data">
    </div>
</div>

@if (count($access) > 0)
    <section class="access">
        @include('user.access.accesslist')
    </section>  
@endif

@include('user.modal.access.registermodal')
@include('user.modal.access.showmodal')
@include('user.modal.access.editmodal')
@include('user.modal.access.deletemodal')

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
            // console.log(url);
        }).done(function (data) {
            $('.access').html(data);
            console.log(data);
        }).fail(function (data) {
            alert('Data Tidak Ditemukan');
            // console.log(data);
        });
    }
});
</script>
@endsection
