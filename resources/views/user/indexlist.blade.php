@if(session('message'))
    <div class="alert alert-success mt-2" role="alert">
        {{session('message')}}
    </div>
@endif
<table class="table mt-2 table-striped table-hover text-center table-bordered" id="datatable">
        <thead class="thead-light">
            <tr>
                <th scope="col" class="align-middle">No</th>
                <th scope="col" class="align-middle">Username</th>
                <th scope="col" class="align-middle text-center">Aksi</th>
            </tr>
        </thead>
        @php
        $i = ($users->currentPage()-1) * $users->perPage()+1 
        @endphp
        @if (isset($msg) && $msg != "")
            <tr><td colspan=3 class="text-center">{{$msg}}</td></tr>
        @endif
        <tbody>
            @foreach ($users as $user)
                <tr class="delete" id="{{$user->id}}">
                    <td scope="row" width="1%" class="align-middle">{{ $i++ }} </td>
                    <td class="align-middle">{{ $user->username }} <input type="hidden" value="{{$user->id}}" ></td>
                    <td class="align-middle text-center"><a href="{{"/". $username . "/editaccess/" . $user->username}}" id="edit-user-access" data-id="{{ $user->id }}" class="btn btn-success">Edit Akses</a>
                        <a href="{{"/". $username ."/daftaruser/" . $user->username . "/edit"}}" id="edit-user" data-id="{{ $user->id }}" class="btn btn-info">Edit User</a>
                        <a href="{{"/". $username ."/daftaruser/" . $user->username}}" id="delete-user" data-id="{{ $user->id }}" class="btn btn-danger">Hapus User</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
<div class="d-flex justify-content-center">{{ $users->onEachSide(2)->links() }}</div>