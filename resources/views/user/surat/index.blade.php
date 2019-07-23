@extends('layouts.layout')

@section('title', 'List Surat')

@include('layouts.header')
@include('layouts.nav')

@section('content')
<h1 class="display-4">List Surat</h1>
<div class="row">
    <div class="col-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#SuratModal">Surat Baru</button>
    </div>
    <div class="col-5">
    </div>
    <div class="col-4">
        <input type="text" name="filter" id="filter" class="d-block pl-2" placeholder="Cari Data">
        <input type="hidden" name="page_hidden" id="page_hidden">
        <input type="hidden" name="url_hidden" id="url_hidden">
    </div>
</div>

@if (count($surats) > 0)
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
                fetch_data(url, page, query);
                window.history.pushState("", "", url);
            });
        // pagination function ends
        
        //search code start
            $('#filter').on('keyup',function(e) {
                var query = $('#filter').val();
                var page = $('#page_hidden').val();
                var url = $('#url_hidden').val();
                fetch_data(url, page, query);
        // end search
            });
        
            function fetch_data(url, page, query='') {
                $.ajax({
                    url : url + '?page=' + page + '&filter=' + query
                }).done(function (data) {
                    $('.surats').html(data);
                    console.log(data);
                }).fail(function () {
                    alert('Data Tidak Ditemukan');
                });
            }
        });
        </script>
@endsection