@extends('layouts.layout')

@section('title', 'List Surat')

@include('layouts.header')
@include('layouts.nav')

@section('content')
<h1 class="display-4">{{$title}}</h1>
<div class="row">
    <div class="col-2">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#SuratModal">Surat Baru</button>
    </div>
    <div class="col-2">
    </div>
    <div class="col-8">
        <input type="text" name="filter" id="filter" class="pl-2" placeholder="Cari Data">
        <input type="date" name="start_date" id="start_date" class="pl-2 mr-1"><sup>s</sup>/<sub>d</sub>
        <input type="date" name="end_date" id="end_date" class="pl-2">
        <input type="button" name="filter_date" id="filter_date" class="btn btn-primary" value="Filter">
        <input type="button" name="reset" id="reset" class="btn btn-warning" value="Reset">
        <input type="hidden" name="page_hidden" id="page_hidden" value="{{ isset($page) ? '?page=$page' : ''}}">
        <input type="hidden" name="url_hidden" id="url_hidden">
        <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
        <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
    </div>
</div>

@if (count($surats) >= 0)
    <section class="surats">
        @include('user.surat.listsurat')
    </section>  
@endif

@include('user.modal.surat.registermodal')
@include('user.modal.surat.showmodal')
@include('user.modal.surat.editmodal')
@include('user.modal.surat.deletemodal')

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
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        fetch_data(url, page, query, start_date, end_date);
        window.history.pushState("", "", url);
    });
// pagination function ends

//search code start
    $('#filter').on('keyup',function(e) {
        $('#page_hidden').val('');
        var query = $('#filter').val();
        var page = "";
        var url = $('#url_hidden').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        fetch_data(url, page, query, start_date, end_date);
// end search
    });

    $('#reset').on('click',function(e) {
        $('#filter').val('');
        $('#start_date').val('');
        $('#end_date').val('');
        var query = "";
        var start_date = "";
        var end_date = "";
        var page = $('#page_hidden').val();
        var url = $('#url_hidden').val();
        fetch_data(url, page, query, start_date, end_date);
    });

    $('#filter_date').on('click',function(e) {
        $('#page_hidden').val('');
        var query = $('#filter').val();
        var page = "";
        var url = $('#url_hidden').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        fetch_data(url, page, query, start_date, end_date);
// end search
    });

    function fetch_data(url, page, query='', start_date='', end_date='') {
        $.ajax({
            url : url + '?page=' + page + '&filter=' + query + '&start_date=' + start_date + '&end_date=' + end_date 
        }).done(function (data) {
            if (data == ""){
                alert('No Data Found');
            }
            $('.surats').html(data);
            console.log(data);
        }).fail(function () {
            alert('Data Tidak Ditemukan');
        });
    }
});
</script>
@endsection