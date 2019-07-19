<table class="table mt-2 table-striped table-hover" id="datatable">
        <thead class="thead-light">
            <tr>
                <th scope="col">No</th>
                <th scope="col">Username</th>
                <th scope="col">Access</th>
                <th scope="col">Control</th>
            </tr>
        </thead>
        @php
        $i = ($users->currentPage()-1) * $users->perPage()+1 
        @endphp
        <tbody>
            @foreach ($users as $user)
                <tr class="delete" id="{{$user->id}}">
                    <td scope="row">{{ $i++ }} </td>
                    <td>{{ $user->username }} <input type="hidden" value="{{$user->id}}" ></td>
                    <td><a href="{{"/". $username ."/" . $user->username}}" id="edit-user" data-id="{{ $user->id }}" class="btn btn-info">Show/Edit</td>
                    <td>
                        <a href="{{"/". $username ."/" . $user->username}}" id="edit-user" data-id="{{ $user->id }}" class="btn btn-info">Edit</a>
                        <a href="{{"/". $username ."/" . $user->username}}" id="delete-user" data-id="{{ $user->id }}" class="btn btn-danger">Hapus</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
<span>{{ $users->links() }}</span>