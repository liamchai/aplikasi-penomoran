<div>
    <div class="modal fade" id="ShowModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="userCrudModal">Info User </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Akses : </label>
                        <input readonly autocomplete="off" type="text" class="form-control name" id="show_name" name="name" value="{{ old('name') }}" placeholder="Contoh : Surat Referensi, Surat Peringatan">
                    </div>
                    <div class="form-group">
                        <label for="URL">URL : </label>
                        <input readonly autocomplete="off" type="text" class="form-control URL" id="show_URL" name="URL" value="{{ old('URL') }}" placeholder="Contoh : surat/suratreferensi, surat/suratperingatan">
                    </div>
                    <div class="form-group">
                        <label for="departemen">Departemen <span class="text-muted font-italic">Optional </span>: </label>
                        <input readonly autocomplete="off" type="text" class="form-control" id="show_departemen" name="departemen" value="{{ old('departemen') }}" placeholder="Contoh: HRD, Admin">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(document).on('click', '#show-access', function (e) {
        e.preventDefault();

    // populate modal
        var id = $(this).data('id');
        var url = $(this).attr('href');
        console.log(id);
        $.get(url, function (data) {
                //success data
                console.log(data);
                $('#show_name').val(data.name);
                $('#show_URL').val(data.url);
                if (data.departemen == null){
                    data.departemen = '-'
                }
                $('#show_departemen').val(data.departemen);
                $('#ShowModal').modal('show');
            }) 
        })
    //end populate modal
});
</script>