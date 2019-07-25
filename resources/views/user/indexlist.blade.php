<table class="table mt-2 table-striped table-hover text-center" id="datatable">
        <thead class="thead-light">
            <tr>
                <th scope="col">No</th>
                <th scope="col">Username</th>
                <th scope="col">Control</th>
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
                    <td scope="row" class="text-right">{{ $i++ }} </td>
                    <td>{{ $user->username }} <input type="hidden" value="{{$user->id}}" ></td>
                    <td><a href="{{"/". $username . "/editaccess/" . $user->username}}" id="edit-user-access" data-id="{{ $user->id }}" class="btn btn-success">Edit Akses</a>
                        <a href="{{"/". $username ."/" . $user->username}}" id="edit-user" data-id="{{ $user->id }}" class="btn btn-info">Edit User</a>
                        <a href="{{"/". $username ."/" . $user->username}}" id="delete-user" data-id="{{ $user->id }}" class="btn btn-danger">Hapus User</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
<div class="d-flex justify-content-center">{{ $users->links() }}</div>