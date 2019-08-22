{{-- Start modal update --}}

<div>
    <div class="modal fade" id="UpdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="userCrudModal">Edit User</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            <form role="form" id="update_form" method="POST" action={{ action('UserController@update', [$username, request('username')]) }}>
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                            <label for="username">Username : </label>
                            <input type="text" class="form-control username" readonly id="username_edit" name="username">
                    </div>
                    <div class="form-group">
                            <label for="password">Password Lama<span class="text-red">* </span>: </label>
                            <input type="password" class="form-control password" id="password_edit" name="password">
                    </div>
                    <div class="form-group">
                            <label for="password_confirmation">Password Baru<span class="text-red">* </span>: </label>
                            <input type="password" class="form-control password_confirmation" id="password_confirmation_edit" name="password_confirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="edit-btn">Simpan</button>
                </div>
            @csrf
            </form>
            </div>
        </div>
    </div>
</div>
{{-- End modal update --}}

<script>
$(document).ready(function () {
    $(document).on('click', '#edit-user', function (e) {
        e.preventDefault();

    // populate modal
        var id = $(this).data('id');
        var url = $(this).attr('href');
        $.get(url, function (data) {
                // utk pagination
                // akhir pagination
                //success data
                $('#username_edit').val(data.name);
                $('#UpdateModal').modal('show');
            }) 
        })
    //end populate modal
    
    // sent http request
    $('#edit-btn').one('click', function (e) {
    
        $('#UpdateModal').on('hidden.bs.modal', function () {
            $(".text-danger").remove();
            $(this).find('form').trigger('reset');
        })
    
    var form = $('#update_form');
        form.submit(function(e) {
            e.preventDefault();
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var filter = $('#filter').val();
            $.ajax({
                url     : form.attr('action') + "/" +  $('#username_edit').val() + '?page=' + $('#page_hidden').val() + '&filter=' + $('#filter').val() + '&sortby=' + $('#hidden_column_name').val() + '&sorttype=' + $('#hidden_sort_type').val() + '&show_data=' + $('#show_data').val(),
                type    : 'POST',
                data    : form.serialize(),
                beforeSend : function(){
                    console.log(filter);
                    if ( $('#password_edit').val() != '' && $('#password_confirmation_edit').val() != '')
                        $('.container-fluid').addClass('block');
                },
                success : function ( json )
                {
                    $('#UpdateModal').modal('hide');
                    $(document.body).removeClass("modal-open");
                    $(".modal-backdrop").remove();
                    // Success
                    // console.log(url);
                    $('.users').html(json);
                    // Do something like redirect them to the dashboard...
                },
                error: function( json )
                {
                    form.find('.text-danger').remove();
                    if(json.status === 422) {
                        var res = json.responseJSON;
                        // console.log(res);
                        form.find('.password').val("");
                        form.find('.password_confirmation').val("");
                        $.each(res.errors, function (key, value) {
                            console.log(value);
                            $('.'+key).closest('.form-group')
                                    .append('<span class="text-danger">'+ value[0] +'</span>');
                        });
                    } else if (json.status === 401){
                        var res = json.responseJSON;
                        $('.password').closest('.form-group').append('<span class="text-danger">'+ res.msg +'</span>');
                        form.find('.password').val("");
                        form.find('.password_confirmation').val("");
                    }
                },
                complete: function(){
                    $('.container-fluid').removeClass('block');
                }
            });
        });
    });
});
</script>