@extends('layouts.layout')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-4"></div>
        {{-- <div class="col-3">
            <img src={{ asset('img/H3FDI1M8_400x400.jpg')}} class="d-block col-4">
        </div>   --}}
        <div class="col-4 my-auto">
        <h1 class="text-center">Login</h1>
        <form method="POST" id="login_form" action="{{ action('UserController@login') }}">
            <div class="form-group">
                <label for="username">Username : </label>
                <input type="text" class="form-control username" id="username" name="username" value={{ old('username') }}>
            </div>
            <div class="form-group">
                <label for="password">Password : </label>
                <input type="password" class="form-control password" id="password" name="password">
            </div>
                <p class="message"></p>
            <input type="submit" class="btn btn-primary w-100" value="Login">
            @csrf
        </form>
        </div>
    </div>
</div>
    <script>
$(document).ready(function () {

    var form = $('#login_form');
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
                window.location.href =  this.url;
            },
            error: function( json )
            {
                form.find('.text-danger').remove();
                console.log(json);
                if(json.status === 422) {
                    var res = json.responseJSON;
                    form.find('.password').val("");
                    $.each(res.errors, function (key, value) {
                        $('.'+key).closest('.form-group')
                                .append('<span class="text-danger">'+ value +'</span>');
                    });
                } else if(json.status === 401){
                    var res = json.responseJSON;
                    form.find('.password').val("");
                    form.find('.message').append('<span class="text-danger">'+ res.message +'</span>');
                }
            }
        });
    });
});
</script>
@endsection