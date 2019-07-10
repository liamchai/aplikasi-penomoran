@extends('layouts.layout')

@section('title', 'List Surat')

@include('layouts.header')
@include('layouts.nav')

@section('content')
<h1>List Surat</h1>
<table class="table mt-2">
        <tr>
            <td>No</td>
            <td>Nama</td>
            <td>Tanggal</td>
        </tr>
        @foreach ($surats as $surat)
        <tr>
            <td>{{$surat->nomor_surat}}</td>
            <td>{{$surat->name}}</td>
            <td>{{date('d M Y', strtotime($surat->tanggal))}}</td>
        </tr>
        @endforeach
        <tr>
        <td colspan="3">{{ $surats->links() }}</td>
        </tr>
@endsection