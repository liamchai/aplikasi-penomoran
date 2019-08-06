@if(session('message'))
    <div class="alert alert-success mt-2" role="alert">
        {{session('message')}}
    </div>
@endif
<table class="table mt-2 table-striped table-hover table-bordered table-responsive" id="datatable">
        <thead class="thead-light">
        <tr>
            <th scope="col" class="align-middle" width="1%">No</th>
            <th scope="col" class="align-middle sorting" data-column_name="name" data-sorting_type="asc" style="cursor:pointer;">Nama <span id="name_icon"><i class="fa fa-sort"></i></th>
            <th scope="col" class="align-middle sorting" data-column_name="URL" data-sorting_type="asc" style="cursor:pointer;">URL <span id="URL_icon"><i class="fa fa-sort"></i></th>
            <th scope="col" class="align-middle sorting" data-column_name="departemen" data-sorting_type="asc" style="cursor:pointer;">Departemen <span id="departemen_icon"><i class="fa fa-sort"></i></th>
            <th scope="col" class="align-middle text-center">Aksi</th>
        </tr>
    </thead>
    @php
    $i = ($access->currentPage()-1) * $access->perPage()+1 
    @endphp
    @if (isset($msg) && $msg != "")
        <tr><td colspan=5 class="text-center">{{$msg}}</td></tr>
    @endif
        @foreach ($access as $acc)
        <tr>
            <td class="align-middle"scope="row">{{$i++}}</td>
            <td class="align-middle">{{$acc->name}}</td>
            <td class="align-middle">{{$acc->url}}</td>
            <td class="align-middle">
            @if ($acc->departemen == "")
                -
            @else
                {{$acc->departemen}}
            @endif
            </td>
            <td class="align-middle text-center">
                <a href="{{"/". $username . "/daftarakses/" . $acc->id}}" data-id="{{ $acc->id }}" id="show-access" class="btn btn-success">Lihat</a>
                <a href="{{"/". $username . "/daftarakses/" . $acc->id . "/edit"}}" data-id="{{ $acc->id }}" id="edit-access" class="btn btn-info">Edit</a>
                <a href="{{"/". $username . "/daftarakses/" . $acc->id}}" data-id="{{ $acc->id }}" id="delete-access" class="btn btn-danger">Hapus</a>
            </td>
        </tr>
        @endforeach
    </table>
<div class="d-flex justify-content-center">{{ $access->onEachSide(2)->links() }}</div>
