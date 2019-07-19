<!-- Modal Register-->
<div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="userCrudModal">User Baru</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            <form role="form" id="register_form" method="POST" action="{{ action('UserController@store', $username) }}">
                <div class="modal-body">
                    <div class="form-group">
                            <label for="username">Username : </label>
                            <input type="text" class="form-control username" id="username_register" name="username" value={{ old('username') }}>
                    </div>
                    <div class="form-group">
                            <label for="password">Password : </label>
                            <input type="password" class="form-control password" id="password_register" name="password">
                    </div>
                    <div class="form-group">
                            <label for="password_confirmation">Ulangi Password : </label>
                            <input type="password" class="form-control password_confirmation" id="password_confirmation_register" name="password_confirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Daftarkan User</button>
                </div>
            @csrf
            </form>
            </div>
            
        </div>
    </div>
</div>
{{-- End modal Register --}}

<script>
$(document).ready(function () {
// modal function start
    $('#exampleModal').on('hidden.bs.modal', function () {
        $(".text-danger").remove();
        $(this).find('form').trigger('reset');
    })

    $('#UpdateModal').on('hidden.bs.modal', function () {
        $(".text-danger").remove();
        $(this).find('form').trigger('reset');
    })

var form = $('#register_form');
$('#btn-save').html('Register');
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
                $('#exampleModal').modal('hide');
                $(document.body).removeClass("modal-open");
                $(".modal-backdrop").remove();
                // Success
                // Do something like redirect them to the dashboard...
                $('.users').html(json);
                console.log(json);
                $('#registermsg').removeClass('d-none');
                setTimeout(function(){
                    $('#registermsg').addClass('d-none'); }, 5000
                );
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
            }
        });
    });
});
</script>