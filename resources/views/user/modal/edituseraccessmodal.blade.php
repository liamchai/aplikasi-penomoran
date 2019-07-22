<div>
    <div class="modal fade" id="EditAccessModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="userCrudModal">Edit Akses <span id="username_edit_access"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" id="edit_access_form" action={{ action('UserController@updateAccess', [$username, request('username')]) }}>
                @method('PATCH')
                <div class="modal-body">
                        {{-- <input type="hidden" name="username_edit_access" id="username_edit_access"> --}}
                        <table class="table">
                                <tr class="items">
                                    <td>Name</td>
                                    <td>Active</td>
                                </tr>
                            </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="edit-user-access-btn">Edit Akses</button>
                </div>
            @csrf
            </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#EditAccessModal').on('hidden.bs.modal', function () {
        $(".item").remove();
        $(this).find('form').trigger('reset');
    })
    // populate modal 
    $(document).on('click', '#edit-user-access', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = $(this).attr('href');
        $.get(url, function (data) {
                $('#username_edit_access').html(data.name);
                var count = data.count;
                var usergranted = data.usergranted;
                $.each(data.access, function (key, value){
                var checked = '';
                for (i = 0; i<count; i++){
                    if (usergranted[i] == value['id']){
                        checked = "checked";
                    }
                }
                    $('.items').closest('.table')
                            .append('<tr class="item">' 
                                    + '<td>' + value['name'] + '</td>'
                                    + '<td>' + '<input type="checkbox"' + checked + ' name="access[]"' + 'value=' + value['id']
                                    + '></td>'
                                    + '</tr>');
                });
                $('#EditAccessModal').modal('show');
            }) 
        })
        //end populate modal

    // sent http request

    $(document).on('click', '#edit-user-access-btn', function (e) {
        var form = $('#edit_access_form');
        form.submit(function(e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url     : form.attr('action') + "/" +  $('#username_edit_access').html(),
                type    : 'PATCH',
                data    : form.serialize(),
                success : function ( json ){
                    $('#EditAccessModal').modal('hide');
                    $(document.body).removeClass("modal-open");
                    $(".modal-backdrop").remove();
                    $('#accessmsg').removeClass('d-none');
                    setTimeout(function(){
                        $('#accessmsg').addClass('d-none'); }, 5000
                    );               },
                error   : function( json )
                {
                    console.log(json);
                }
            })
        })
    });
});
</script>