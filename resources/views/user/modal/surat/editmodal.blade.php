<div>
    <div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="userCrudModal">Edit Surat</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            <form role="form" id="EditForm" method="POST" action={{ action('LetterController@update', [$username, request('url')]) }}>
                @method('PATCH')
                <div class="modal-body">
                    <input type="hidden" name="user" id="user" value={{$username}}>
                    <input type="hidden" name="nomor" id="nomor">
                    <div class="form-group">
                        <label for="nomor">Nomor Surat : </label>
                        <input readonly autocomplete="off" type="text" class="form-control" id="edit_nomor" name="nomor_surat">
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal Keluar : </label>
                        <input readonly autocomplete="off" type="text" class="form-control" id="edit_tanggal" name="tanggal">
                    </div>
                    <div class="form-group">
                        <label for="pembuat">Dikeluarkan oleh : </label>
                        <input readonly autocomplete="off" type="text" class="form-control" id="edit_pembuat" name="pembuat">
                    </div>
                    <div class="form-group">
                        <label for="nomor">Kepada : </label>
                        <input autocomplete="off" type="text" class="form-control" id="edit_penerima" name="penerima">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            @csrf
            </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
// modal function start
    $('#SuratModal').on('hidden.bs.modal', function () {
        $(".text-danger").remove();
        $(this).find('form').trigger('reset');
    })

    $('#EditModal').on('hidden.bs.modal', function () {
        $(".text-danger").remove();
        $(this).find('form').trigger('reset');
    })

    $(document).on('click', '#edit-surat', function (e) {
        e.preventDefault();
        
    // populate modal
        var id = $(this).data('id');
        var url = $(this).attr('href');     
        $.get(url, function (data) {
                //success data
                // console.log(page);
                
                $('#id').val(id);
                $('#nama_surat').html(data.name);
                $('#edit_penerima').val(data.penerima);
                $('#edit_nomor').val(data.nomor_surat);
                $('#edit_tanggal').val(data.tanggal);
                $('#edit_pembuat').val(data.submitted_by);
                if (data.departemen == null){
                    data.departemen = '-'
                }
                $('#show_departemen').val(data.departemen);
                $('#EditModal').modal('show');
            }) 
        })

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
            url     : form.attr('action') + "/" + $('#id').val() + '?page=' + $('#page_hidden').val() + '&filter=' + $('#filter').val() + '&start_date=' + $('#start_date').val() + '&end_date=' + $('#end_date').val() + '&sortby=' + $('#hidden_column_name').val() + '&sorttype=' + $('#hidden_sort_type').val() + '&show_data=' + $('#show_data').val(),
            type    : 'POST',
            data    : form.serialize(),
            beforeSend : function(){
                $('.container-fluid').addClass('block');
            },
            success : function ( json )
            {
                $('#EditModal').modal('hide');
                $(document.body).removeClass("modal-open");
                $(".modal-backdrop").remove();
                // Success
                // Do something like redirect them to the dashboard...
                $('.surats').html(json);
                if (order_type == 'desc'){
                    $('#'+column_name+'_icon').html('<i class="fa fa-caret-down" aria-hidden="true"></i>');
                }
                if (order_type == 'asc'){
                    $('#'+column_name+'_icon').html('<i class="fa fa-caret-up" aria-hidden="true"></i>');
                }
            },
            error: function( json )
            {
                form.find('.text-danger').remove();
                if(json.status === 422) {
                    var res = json.responseJSON;
                    // console.log(res);
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
        });
    });
});
</script>