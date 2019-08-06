@if(session('message'))
    <div class="alert alert-success mt-2" role="alert">
        {{session('message')}}
    </div>
@endif
<table class="table mt-2 table-striped table-hover table-bordered table-responsive">
    <thead class="thead-light text-center">
        <tr>
            <th class="align-middle" style="cursor:pointer;" width="1%" scope="col">No</th>
            <th class="align-middle sorting" data-sorting_type="asc" data-column_name="nomor_surat" style="cursor:pointer;" scope="col">Nomor Surat <span id="nomor_surat_icon"><i class="fa fa-sort"></i></span></th>
            <th class="align-middle sorting" data-sorting_type="asc" data-column_name="name" style="cursor:pointer;" scope="col">Jenis Surat <span id="name_icon"><i class="fa fa-sort"></i></span></th>
            @if ($user == 'test')
                <th scope="col" class="sorting" data-sorting_type="asc" data-column_name="submitted_by" style="cursor:pointer;">Dibuat oleh <span id="submitted_by_icon"><i class="fa fa-sort"></i></span></th>
            @endif
            <th class="align-middle sorting" data-sorting_type="asc" data-column_name="tanggal" scope="col" style="cursor:pointer;">Tanggal <span id="tanggal_icon"><i class="fa fa-sort"></i></span></th>
            <th class="align-middle sorting" data-sorting_type="asc" data-column_name="penerima" scope="col" style="cursor:pointer;">Penerima <span id="penerima_icon"><i class="fa fa-sort"></i></span></th>
            <th class="align-middle text-center" scope="col" style="cursor:pointer;">Aksi</th>
        </tr>
    </thead>
    @php
        $i = ($surats->currentPage()-1) * $surats->perPage()+1 
    @endphp
    {{-- <input type="hidden" name="page" value={{$surats->currentPage()}}> --}}
    @if (isset($msg) && $msg != "")
        <tr><td colspan={{ ($user=='test') ? 7 : 6}} class="text-center">{{$msg}}</td></tr>
    @endif
        @foreach ($surats as $surat)
        <tr class="item"
        {{-- id="show-surat" style="cursor:pointer;" data-id="{{ $surat->id }}" href="{{ "/" .$user . "/daftarsurat/" . $surat->id }}" --}}
        >
            <td class="align-middle" scope="row">{{$i++}}</td>
            <td class="align-middle">{{$surat->nomor_surat}}</td>
            <td class="align-middle">{{$surat->name}}</td>
            @if ($user == 'test')
                <td class="align-middle">{{ $surat->submitted_by }}</td>
            @endif
            <td class="align-middle">{{date('d M Y', strtotime($surat->tanggal))}}</td>
            <td class="align-middle">
            @if ($surat->penerima == "")
                -
            @else
                {{$surat->penerima}}
            @endif
            </td>
            <td class="align-middle text-center">
                <a href="{{ "/" .$user . "/daftarsurat/" . $surat->id }}" id="show-surat" data-id="{{ $surat->id }}" class="btn btn-success">Lihat</a>
                <a href="{{ "/" .$user . "/daftarsurat/" . $surat->id . "/edit"}}" id="edit-surat" data-id="{{ $surat->id }}" class="btn btn-info">Edit</a>
                <a href="{{ "/" .$user . "/daftarsurat/" . $surat->id }}" id="delete-surat" data-id="{{ $surat->id }}" class="btn btn-danger">Hapus</a>
            </td>
        </tr>
        @endforeach
    </table>
<div class="d-flex justify-content-center">{{ $surats->onEachSide(1)->links() }}</div>
