<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="delete_form" method="POST" action={{ action('UserController@destroy', [$username, request('username')]) }}>
                @method('DELETE')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data ini?</p>
                    <p>Username : <span id="username_delete"></span><br>
                    <input type="hidden" id="delete_token"/>
                    <input type="hidden" id="id"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger" id="delete-btn">Hapus</button>
                </div>
                @csrf
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(document).on('click', '#delete-user', function (e) {
        e.preventDefault();
    // populate modal
        var id = $(this).data('id');
        var url = $(this).attr('href');
        $.get(url, function (data) {
                console.log(data);
                // success data
                // utk pagination
                // akhir pagination
                // console.log(id);
                $('#id').val(id);
                $('#username_delete').html(data.username);
                $('#DeleteModal').modal('show');
            }) 
        })
    //end populate modal
    
    // sent http request
    $('#delete-btn').one('click', function (e) {

        $('#DeleteModal').on('hidden.bs.modal', function () {
            $(".text-danger").remove();
            $(this).find('form').trigger('reset');
        })
    
    var form = $('#delete_form');
        form.submit(function(e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url     : form.attr('action') + "/" +  $('#username_delete').html() + '?page=' + $('#page_hidden').val() + '&filter=' + $('#filter').val() + '&sortby=' + $('#hidden_column_name').val() + '&sorttype=' + $('#hidden_sort_type').val() + '&show_data=' + $('#show_data').val(),
                type    : 'POST',
                data    : form.serialize(),
                id      : $('#id').val(),
                // timeout : 200,
                beforeSend : function(){
                    $('.container-fluid').addClass('block');
                },
                success : function ( json )
                {
                    $('#DeleteModal').modal('hide');
                    $(document.body).removeClass("modal-open");
                    $(".modal-backdrop").remove();
                    $('.users').html(json);
                },
                error: function (json)
                {
                    alert('gagal menghapus.');
                },
                complete: function(){
                    $('.container-fluid').removeClass('block');
                }
            });
        });
    });
});
</script>