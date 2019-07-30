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
                        <label for="nama">Jenis Surat : </label>
                        <select name="nama" id="nama" name="select_nama_surat" class="form-control">
                            <option disabled selected>Silahkan pilih Jenis Surat</option>
                            @foreach ($dropdown as $list)
                                <option value="{{ $list->url }}">{{ $list->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nomor">Nomor Surat : </label>
                        <input readonly autocomplete="off" type="text" class="form-control" id="register_nomor" name="nomor_surat">
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

    $('select[name="nama"]').on('change', function (e) {
        var url = "/" + $('#user').val() + "/" + $('#nama').val();
        console.log(url);
        $.get(url, function (data) {
            var nomor = data.no + "/" + data.departemen + "/" + data.month + "/" + data.year;
            var tanggal = data.date;
            var pembuat = data.username;
            var url = data.url;
            console.log(data);
            $('#url').val(url);
            $('#nomor').val(data.no);
            $('#register_nomor').val(nomor);
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
            url     : form.attr('action'),
            type    : form.attr('method'),
            data    : form.serialize(),
            success : function ( json )
            {
                $('#SuratModal').modal('hide');
                $(document.body).removeClass("modal-open");
                $(".modal-backdrop").remove();
                // Success
                // Do something like redirect them to the dashboard...
                $('.surats').html(json);
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
            }
        });
    });
});
</script>