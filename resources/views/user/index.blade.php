@extends('layouts.layout')

@section('title', $title)

@include('layouts.header')
@include('layouts.nav')

@section('content')
<div>
<h1>{{$title}}</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Register New User</button>

@if (session('msg'))
    <div class="alert alert-success mt-2" role="alert">
        {{ session('msg') }}
    </div>
@endif
<table class="table mt-2 table-striped table-hover" id="datatable">
    <thead class="thead-light">
        <tr>
            <th scope="col">No</th>
            <th scope="col">Username</th>
            <th scope="col">Access</th>
        </tr>
    </thead>
    @php
    $i = ($users->currentPage()-1) * $users->perPage()+1 
    @endphp
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td scope="row">{{ $i++ }} </td>
                <td>{{ $user->username }} </td>
                <td><a href="{{"/". $username ."/" . $user->username}}" >Show/Edit</td>
            </td>
        @endforeach
    </tbody>
</table>
<span>{{ $users->links() }}</span>

</div>


<!-- Modal -->
<div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Register</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form role="form" id="register_form" method="POST" action="{{ action('UserController@store', $username) }}">
        <div class="modal-body">
            {{-- {{ dd(action('UserController@store')) }} --}}
            <div class="form-group">
                    <label for="username">Username : </label>
                    <input type="text" class="form-control username" id="username" name="username" value={{ old('username') }}>
            </div>
            <div class="form-group">
                    <label for="password">Password : </label>
                    <input type="password" class="form-control password" id="password" name="password">
            </div>
            <div class="form-group">
                    <label for="password_confirmation">Confirm Password : </label>
                    <input type="password" class="form-control password_confirmation" id="password_confirmation" name="password_confirmation">
            </div>
        </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
        @csrf
    </form>
</div>
</div>
</div>
</div>


<script>
$(document).ready(function () {

    $('#exampleModal').on('hidden.bs.modal', function () {
        $("span").remove();
        $(this).find('form').trigger('reset');
    })

var form = $('#register_form');
form.submit(function(e) {
    e.preventDefault();

    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url     : form.attr('action'),
        type    : form.attr('method'),
        data    : form.serialize(),
        success : function ( json )
        {
            // Success
            // Do something like redirect them to the dashboard...
            $('#exampleModal').modal('hide');
            $(".modal-backdrop").remove();
            console.log(json);
        },
        error: function( json )
        {
            console.log(this.url);
            form.find('.text-danger').remove();
            if(json.status === 422) {
                var res = json.responseJSON;
                console.log(res);
                form.find('.password').val("");
                form.find('.password_confirmation').val("");
                $.each(res.errors, function (key, value) {
                    console.log(value);
                    $('.'+key).closest('.form-group')
                            .append('<span class="text-danger">'+ value[0] +'</span>');
                });
            }
            // else if(json.status === 401){
            //     var res = json.responseJSON;
            //     form.find('.password').val("");
            //     form.find('.message').append('<span class="text-danger">'+ res.message +'</span>');
            // }
        }
    });
});
});
</script>
@endsection