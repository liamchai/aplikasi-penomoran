<div>
    <div class="modal fade" id="RegisterAccessModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="userCrudModal">Akses Baru</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            <form role="form" id="RegisterAccessForm" method="POST" action={{ action('AccessController@store', $username) }}>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Akses<span class="text-red">* </span> : </label>
                        <input autocomplete="off" type="text" class="form-control name" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh : Surat Referensi, Surat Peringatan">
                    </div>
                    <div class="form-group">
                        <label for="URL">URL<span class="text-red">* </span> : </label>
                        <input autocomplete="off" type="text" class="form-control URL" id="URL" name="URL" value="{{ old('URL') }}" placeholder="Contoh : surat/suratreferensi, surat/suratperingatan">
                    </div>
                    <div class="form-group">
                        <label for="departemen">Departemen<span class="text-red">* </span>: </label>
                        <input autocomplete="off" type="text" class="form-control departemen" id="departemen" name="departemen" value="{{ old('departemen') }}" placeholder="Contoh: HRD, Admin">
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
    $('#RegisterAccessModal').on('hidden.bs.modal', function () {
        $(".text-danger").remove();
        $(this).find('form').trigger('reset');
    })

    $('#EditModal').on('hidden.bs.modal', function () {
        $(".text-danger").remove();
        $(this).find('form').trigger('reset');
    })

var form = $('#RegisterAccessForm');
    form.submit(function(e) {
        e.preventDefault();
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url     : form.attr('action') + '?page=' + '&filter=' + $('#filter').val() + '&start_date=' + $('#start_date').val() + '&end_date=' + '&sortby=' + '&sorttype=' + $('#hidden_sort_type').val() + '&show_data=' + $('#show_data').val(),
            type    : form.attr('method'),
            data    : form.serialize(),
            beforeSend : function(){
                if( $('#name').val() != '' && $('#URL').val() != '' && $('#departemen').val() != ''){
                    $('.container-fluid').addClass('block');
                }
            },
            success : function ( json )
            {
                $('#RegisterAccessModal').modal('hide');
                $(document.body).removeClass("modal-open");
                $(".modal-backdrop").remove();
                // Success
                // Do something like redirect them to the dashboard...
                $('#registermsg').removeClass('d-none');
                $('.access').html(json);
                $('#page_hidden').val('');
                $('#hidden_column_name').val('');
                $('#hidden_sort_type').val('');
                console.log(json);
            },
            error: function( json )
            {
                console.log(json);
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
            complete: function()
            {
                $('.container-fluid').removeClass('block');
            }
        });
    });
});
</script>