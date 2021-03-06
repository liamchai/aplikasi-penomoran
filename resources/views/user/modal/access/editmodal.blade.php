<div>
    <div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="userCrudModal">Edit Akses</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            <form role="form" id="EditForm" method="POST" action={{ action('AccessController@update', [$username, request('name')]) }}>
                @method('PATCH')
                <div class="modal-body">
                    <input type="hidden" id="edit_hidden_id" name="id">
                    <div class="form-group">
                        <label for="name">Nama Akses<span class="text-red">* </span> : </label>
                        <input autocomplete="off" type="text" class="form-control name" id="edit_name" name="name" placeholder="Contoh : Surat Referensi, Surat Peringatan">
                    </div>
                    <div class="form-group">
                        <label for="URL">URL<span class="text-red">* </span> : </label>
                        <input autocomplete="off" type="text" class="form-control URL" id="edit_URL" name="URL" placeholder="Contoh : surat/suratreferensi, surat/suratperingatan">
                    </div>
                    <div class="form-group">
                        <label for="departemen">Departemen<span class="text-red">* </span>: </label>
                        <input autocomplete="off" type="text" class="form-control" id="edit_departemen" name="departemen" placeholder="Contoh: HRD, Admin">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="edit-access-btn">Simpan</button>
                </div>
            @csrf
            </form>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function () {
    $('#RegisterAccessModal').on('hidden.bs.modal', function () {
        $(".text-danger").remove();
        $(this).find('form').trigger('reset');
    })

    $('#EditModal').on('hidden.bs.modal', function () {
        $(".text-danger").remove();
        $(this).find('form').trigger('reset');
    })

    $(document).on('click', '#edit-access',function (e) {
        e.preventDefault();

    // populate modal
        var id = $(this).data('id');
        var url = $(this).attr('href');
        $.get(url, function (data) {
                // success data
                console.log(data);
                $('#edit_hidden_id').val(data.id);
                $('#edit_name').val(data.name);
                $('#edit_URL').val(data.url);
                if (data.departemen == null){
                    data.departemen = '-'
                }
                $('#edit_departemen').val(data.departemen);
                $('#EditModal').modal('show');
            }) 
        })
    //end populate 
    $('#edit-access-btn').one('click', function (e) {
        var form = $('#EditForm');
        form.submit(function(e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var order_type = $('#hidden_sort_type').val();
            var column_name = $('#hidden_column_name').val();
            $.ajax({
                url     : form.attr('action') + "/" +  $('#edit_hidden_id').val() + '?page=' + $('#page_hidden').val() + '&filter=' + $('#filter').val() + '&sortby=' + $('#hidden_column_name').val() + '&sorttype=' + $('#hidden_sort_type').val() + '&show_data=' + $('#show_data').val(),
                type    : 'POST',
                data    : form.serialize(),
                beforeSend : function(){
                    $('.container-fluid').addClass('block');
                },
                success : function ( json ){
                    $('.access').html(json);
                    $('#EditModal').modal('hide');
                    $(document.body).removeClass("modal-open");
                    $(".modal-backdrop").remove();
                    if (order_type == 'desc'){
                        $('#'+column_name+'_icon').html('<i class="fa fa-caret-down" aria-hidden="true"></i>');
                    }
                    if (order_type == 'asc'){
                        $('#'+column_name+'_icon').html('<i class="fa fa-caret-up" aria-hidden="true"></i>');
                    }
                },
                error: function( json )
                {
                    console.log(json);
                    form.find('.text-danger').remove();
                    if(json.status === 422) {
                        var res = json.responseJSON;
                        console.log(res);
                        $.each(res.errors, function (key, value) {
                            console.log(key,value);
                            $('.'+key).closest('.form-group')
                                    .append('<span class="text-danger">'+ value[0] +'</span>');
                        });
                    }
                },
                complete: function(){
                    $('.container-fluid').removeClass('block');
                }
            })
        })
    });
});
</script>