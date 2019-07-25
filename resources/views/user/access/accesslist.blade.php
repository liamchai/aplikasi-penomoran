<table class="table mt-2 table-striped table-hover" id="datatable">
        <thead class="thead-light">
        <tr>
            <th scope="col">No</th>
            <th scope="col">Name</th>
            <th scope="col">URL</th>
            <th scope="col">Control</th>
        </tr>
    </thead>
    @php
    $i = ($access->currentPage()-1) * $access->perPage()+1 
    @endphp
    @if (isset($msg) && $msg != "")
        <tr><td colspan=4 class="text-center">{{$msg}}</td></tr>
    @endif
        @foreach ($access as $acc)
        <tr>
            <td scope="row">{{$i++}}</td>
            <td>{{$acc->name}}</td>
            <td>{{$acc->url}}</td>
            <td>
                <a href="{{"/". $username . "/daftarakses/" . $acc->id}}" data-id="{{ $acc->id }}" id="show-access" class="btn btn-success">Lihat</a>
                <a href="{{"/". $username . "/daftarakses/" . $acc->id . "/edit"}}" data-id="{{ $acc->id }}" id="edit-access" class="btn btn-info">Edit</a>
                <a href="{{"/". $username . "/daftarakses/" . $acc->id}}" data-id="{{ $acc->id }}" id="delete-access" class="btn btn-danger">Hapus</a>
            </td>
        </tr>
        @endforeach
    </table>
<div class="d-flex justify-content-center">{{ $access->links() }}</div>
