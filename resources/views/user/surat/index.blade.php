@extends('layouts.layout')

@section('title', 'List Surat')

@section('content')
@include('layouts.header')
@include('layouts.nav')
<h1 class="display-4">{{$title}}</h1>
<div class="row">
    <div class="col-2">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#SuratModal">Surat Baru</button>
    </div>
    <div class="col-2">
        
    </div>
    <div class="col-8">
        <label for="show_data">Banyak Data :</label>
        <select name="show_data" id="show_data">
            <option selected value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
        <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <label for="filter_data">Filter Data :</label>
        <input type="text" name="filter" id="filter" class="pl-2" placeholder="Cari Data">
        <br>
        <label for="filter_tanggal">Filter Tanggal :</label>
        <input type="date" name="start_date" id="start_date" class="pl-2 mr-1"><sup>s</sup>/<sub>d</sub>
        <input type="date" name="end_date" id="end_date" class="pl-2">
        <input type="button" name="filter_date" id="filter_date" class="btn btn-primary" value="Filter">
        <input type="button" name="reset" id="reset" class="btn btn-warning" value="Reset">
        <input type="hidden" name="page_hidden" id="page_hidden" value="{{ isset($page) ? '?page=$page' : ''}}">
        <input type="hidden" name="url_hidden" id="url_hidden">
        <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
        <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc" />
        <input type="hidden" name="hidden_reverse_type" id="hidden_reverse_type" value="desc" />
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

    $('select[name="show_data"]').on('change', function (e) {
        $('#page_hidden').val('');
        var show_data = $('#show_data').val();
        var query = $('#filter').val();
        var page = "";
        var url = $('#url_hidden').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var column_name = $('#hidden_column_name').val();
        var order_type = $('#hidden_sort_type').val();
        fetch_data(url, page, query, start_date, end_date, column_name, order_type, show_data);
    });
// pagination function start
    $('body').on('click', '.sorting', function(){
        var column_name = $(this).data('column_name');
        var order_type = $('#hidden_reverse_type').val();
        var show_data = $('#show_data').val();
        var reverse_order = '';
        if (order_type == 'asc'){
            reverse_order = 'desc';
        }
        if(order_type == 'desc'){
            reverse_order = 'asc';  
        }
        $('#hidden_column_name').val(column_name);
        $('#hidden_sort_type').val(reverse_order);
        $('#hidden_reverse_type').val(reverse_order);
        var page = $('#hidden_page').val();
        var query = $('#filter').val();
        var url = $('#url_hidden').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        fetch_data(url, page, query, start_date, end_date, column_name, reverse_order, show_data);
    });

    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href').split('page=')[0];
        var url = url.replace('?', '');
        $('#url_hidden').val(url);

        var page = $(this).attr('href').split('page=')[1];
        $('#page_hidden').val(page);
        var column_name = $('#hidden_column_name').val();
        var order_type = $('#hidden_sort_type').val();
        var query = $('#filter').val();  
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var show_data = $('#show_data').val();
        fetch_data(url, page, query, start_date, end_date, column_name, order_type, show_data);
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
        var column_name = $('#hidden_column_name').val();
        var order_type = $('#hidden_sort_type').val();
        var show_data = $('#show_data').val();
        fetch_data(url, page, query, start_date, end_date, column_name, order_type, show_data);
// end search
    });

    $('#reset').on('click',function(e) {
        $('#filter').val('');
        $('#start_date').val('');
        $('#end_date').val('');
        $('#hidden_column_name').val('');
        $('#hidden_sort_type').val('');
        $('#show_data').val(10);
        var show_data = 10;
        var query = "";
        var start_date = "";
        var end_date = "";
        var page = "";
        var column_name = "";
        var order_type = "";
        var url = $('#url_hidden').val();
        fetch_data(url, page, query, start_date, end_date, column_name, order_type, show_data);
    });

    $('#filter_date').on('click',function(e) {
        $('#page_hidden').val('');
        var query = $('#filter').val();
        var page = "";
        var url = $('#url_hidden').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var column_name = $('#hidden_column_name').val();
        var show_data = $('#show_data').val();
        var order_type = $('#hidden_sort_type').val();
        fetch_data(url, page, query, start_date, end_date, column_name, order_type, show_data);
// end search
    });

    function fetch_data(url, page, query='', start_date='', end_date='', column_name='', order_type= '', show_data='') {
        $.ajax({
            url : url + '?page=' + page + '&filter=' + query + '&start_date=' + start_date + '&end_date=' + end_date + '&sortby=' + column_name + '&sorttype=' + order_type + '&show_data=' + show_data,
            beforeSend : function(){
                $('.container-fluid').addClass('block');
            },
            complete : function(){
                $('.container-fluid').removeClass('block');
            },
        }).done(function (data) {
            if (data == ""){
                alert('No Data Found');
            }
            $('.surats').html(data);
            if (order_type == 'desc'){
                $('#'+column_name+'_icon').html('<i class="fa fa-caret-down" aria-hidden="true"></i>');
            }
            if (order_type == 'asc'){
                $('#'+column_name+'_icon').html('<i class="fa fa-caret-up" aria-hidden="true"></i>');
            }
            console.log(url);
        }).fail(function () {
            alert('Data Tidak Ditemukan');
        });
    }
});
</script>
@endsection