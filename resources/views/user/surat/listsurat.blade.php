@extends('layouts.layout')

@section('title', 'List Surat')

@include('layouts.header')
@include('layouts.nav')

@section('content')
<h1>List Surat</h1>
@if (session('msg'))
    <div class="alert alert-success mt-2" role="alert">
        {{ session('msg') }}
    </div>
@endif
<table class="table mt-2 table-striped">
    <thead class="thead-light">
        <tr>
            <th scope="col">No</th>
            <th scope="col">Nomor Surat</th>
            <th scope="col">Nama Surat</th>
            @if ($user == 'test')
                <th scope="col">Dibuat oleh</th>
            @endif
            <th scope="col">Tanggal</th>
        </tr>
    </thead>
    @php
        $i = ($surats->currentPage()-1) * $surats->perPage()+1 
    @endphp
        @foreach ($surats as $surat)
        <tr>
            <td scope="row">{{$i++}}</td>
            <td>{{$surat->nomor_surat}}</td>
            <td>{{$surat->name}}</td>
            @if ($user == 'test')
                <td>{{ $surat->submitted_by }}</td>
            @endif
            <td>{{date('d M Y', strtotime($surat->tanggal))}}</td>
        </tr>
        @endforeach
    </table>
    <div class="text-center mx-auto d-block">
        <span>{{ $surats->links() }}</span>
    </div>
@endsection