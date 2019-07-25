<table class="table mt-2 table-striped table-hover">
    <thead class="thead-light">
        <tr>
            <th scope="col">No</th>
            <th scope="col">Nomor Surat</th>
            <th scope="col">Jenis Surat</th>
            @if ($user == 'test')
                <th scope="col">Dibuat oleh</th>
            @endif
            <th scope="col">Tanggal</th>
            <th scope="col">Penerima</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    @php
        $i = ($surats->currentPage()-1) * $surats->perPage()+1 
    @endphp
    @if (isset($msg) && $msg != "")
        <tr><td colspan={{ ($user=='test') ? 7 : 6}} class="text-center">{{$msg}}</td></tr>
    @endif
        @foreach ($surats as $surat)
        <tr class="item"
        {{-- id="show-surat" style="cursor:pointer;" data-id="{{ $surat->id }}" href="{{ "/" .$user . "/daftarsurat/" . $surat->id }}" --}}
        >
            <td scope="row">{{$i++}}</td>
            <td>{{$surat->nomor_surat}}</td>
            <td>{{$surat->name}}</td>
            @if ($user == 'test')
                <td>{{ $surat->submitted_by }}</td>
            @endif
            <td>{{date('d M Y', strtotime($surat->tanggal))}}</td>
            @if ($surat->penerima == "")
                <td>-</td>
            @else
                <td>{{$surat->penerima}}</td>
            @endif
            <td>
                <a href="{{ "/" .$user . "/daftarsurat/" . $surat->id }}" id="show-surat" data-id="{{ $surat->id }}" class="btn btn-success">Lihat</a>
                <a href="{{ "/" .$user . "/daftarsurat/" . $surat->id . "/edit"}}" id="edit-surat" data-id="{{ $surat->id }}" class="btn btn-info">Edit</a>
                <a href="{{ "/" .$user . "/daftarsurat/" . $surat->id }}" id="delete-surat" data-id="{{ $surat->id }}" class="btn btn-danger">Hapus</a>
            </td>
        </tr>
        @endforeach
    </table>
<div class="d-flex justify-content-center">{{ $surats->links() }}</div>
