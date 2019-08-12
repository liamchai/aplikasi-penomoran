<div>
    <div class="modal fade" id="SuratModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="userCrudModal">Surat Baru</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            <form role="form" id="SuratForm" method="POST" action={{ action('LetterController@store', $username) }}>
                <div class="modal-body">
                    <input type="hidden" name="user" id="user" value={{$username}}>
                    <input type="hidden" name="url" id="url">
                    <input type="hidden" name="nomor" id="nomor">
                    <div class="form-group">
                        <label for="nama">Jenis Surat<span class="text-red">* </span> : </label>
                        <select name="nama" id="nama" class="form-control">
                            <option readonly selected value="0">Silahkan pilih Jenis Surat</option>
                            @foreach ($dropdown as $list)
                                <option value="{{ $list->url }}">{{ $list->name }}</option>
                            @endforeach
                        </select>
                        <span class="nama_surat"></span>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal Keluar : </label>
                        <input readonly autocomplete="off" type="text" class="form-control" id="register_tanggal" name="tanggal">
                    </div>
                    <div class="form-group">
                        <label for="pembuat">Dikeluarkan oleh : </label>
                        <input readonly autocomplete="off" type="text" class="form-control" id="register_pembuat" name="pembuat">
                    </div>
                    <div class="form-group">
                        <label for="nomor">Kepada : </label>
                        <input autocomplete="off" type="text" class="form-control" id="register_penerima" name="penerima">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="register-btn">Simpan</button>
                </div>
            @csrf
            </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="showSurat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="nama_register"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                    <table>
                        <tr>
                            <td>Nama Surat</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><span class="nama_register"></span></td>
                        </tr>
                        <tr>
                            <td>Nomor Surat</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><span id="nomor_register"></span></td>
                        </tr>
                        <tr>
                            <td>Tanggal Keluar</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><span id="tanggal_register"></span></td>
                        </tr>
                        <tr>
                            <td>Dikeluarkan oleh</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><span id="pembuat_register"></span></td>
                        </tr>
                        <tr>
                            <td>Kepada</td>
                            <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                            <td><span id="penerima_register"></span></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
// modal function start
    $('#SuratModal').on('hidden.bs.modal', function () {
        $(".text-danger").remove();
        $('#url').val('');
        $(this).find('form').trigger('reset');
    })

    $('#EditModal').on('hidden.bs.modal', function () {
        $(".text-danger").remove();
        $(this).find('form').trigger('reset');
    })

    $('select[name="nama"]').on('change', function (e) {
        var url = "/" + $('#user').val() + "/" + $('#nama').val();
        $('.nama_surat').find(".text-danger").remove();
        console.log(url);
        $.get(url, function (data) {
            // console.log(json);
            var tanggal = data.date;
            var pembuat = data.username;
            var url = data.url;
            $('#url').val(url);
            $('#nomor').val(data.no);
            $('#register_tanggal').val(tanggal);
            $('#register_pembuat').val(pembuat);
        })
    })

var form = $('#SuratForm');
    form.submit(function(e) {
        e.preventDefault();
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url     : form.attr('action') + '?page=' + '&filter=' + $('#filter').val() + '&start_date=' + $('#start_date').val() + '&end_date=' + $('#end_date').val() + '&sortby=' + $('#hidden_column_name').val() + '&sorttype=' + $('#hidden_sort_type').val() + '&show_data=' + $('#show_data').val(),
            type    : form.attr('method'),
            data    : form.serialize(),
            beforeSend : function(){
                if( $('select[name="nama"]').val() != 0 ){
                    $('.container-fluid').addClass('block');
                }
            },
            success : function ( json )
            {
                $('#SuratModal').modal('hide');
                $(document.body).removeClass("modal-open");
                $(".modal-backdrop").remove();
                $('#register-btn').prop('disabled', false);
                $('#nomor').val('');
                $('#page_hidden').val('');
                $('#hidden_column_name').val('');
                $('#hidden_sort_type').val('');
                var url = "/" + $('#user').val() + "/infosurat/" + $('#url').val();
                $.get(url, function(data){
                    $('.nama_register').html(data[0].name);
                    $('#nomor_register').html(data[0].nomor_surat);
                    $('#tanggal_register').html(data[1]);
                    $('#pembuat_register').html(data[0].submitted_by);
                    if(data.penerima == null){
                        data.penerima = "-";
                    }
                    $('#penerima_register').html(data.penerima);
                    $('#showSurat').modal('show');
                })
                // Success
                // Do something like redirect them to the dashboard...
                $('.surats').html(json);
            },
            error: function( json )
            {
                form.find('.text-danger').remove();
                if(json.status === 422) {
                    var res = json.responseJSON;
                    // console.log(res);
                    $.each(res.errors, function (key, value) {
                        $('.'+key).closest('.form-group')
                                .append('<span class="text-danger">'+ value[0] +'</span>');
                    });
                }
                else if(json.status === 409) {
                    alert('Terjadi error Silahkan buat surat baru lagi');
                    $('#SuratModal').modal('hide');
                    $(document.body).removeClass("modal-open");
                    $(".modal-backdrop").remove();
                }
                else if(json.status === 403) {
                    alert('Terjadi error Silahkan refresh');
                    $('#SuratModal').modal('hide');
                    $(document.body).removeClass("modal-open");
                    $(".modal-backdrop").remove();
                }
                else if(json.status === 500) {
                    $('.nama_surat').append('<span class="text-danger">Harap Pilih Jenis Surat</span>');
                }
            },
            complete: function(){
                $('.container-fluid').removeClass('block');
            }
        });
    });
});
</script>