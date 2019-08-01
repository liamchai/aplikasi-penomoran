<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="delete_form" method="POST" action={{ action('LetterController@destroy', [$username, request('username')]) }}>
                    @method('DELETE')
                    <div class="modal-body">
                        <input type="hidden" name="page" id="page">
                        <input type="hidden" name="query" id="query_delete">
                        <input type="hidden" name="tanggal_mulai" id="tanggal_mulai_delete">
                        <input type="hidden" name="tanggal_berakhir" id="tanggal_berakhir_delete">
                        <p>Apakah Anda yakin ingin menghapus data ini?</p>
                        <table>
                            <tr>
                                <td>Nama Surat</td>
                                <td>:</td>
                                <td><span id="nama_delete"></span></td>
                            </tr>
                            <tr>
                                <td>Nomor Surat</td>
                                <td>:</td>
                                <td><span id="nomor_delete"></span></td>
                            </tr>
                            <tr>
                                <td>Tanggal Keluar</td>
                                <td>:</td>
                                <td><span id="tanggal_delete"></span></td>
                            </tr>
                            <tr>
                                <td>Dikeluarkan oleh</td>
                                <td>:</td>
                                <td><span id="pembuat_delete"></span></td>
                            </tr>
                        <input type="hidden" id="delete_token"/>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger" id="delete-surat-btn">Hapus</button>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
    
    <script>
    $(document).ready(function () {
        $(document).on('click', '#delete-surat', function (e) {
            e.preventDefault();
            $('#SuratModal').on('hidden.bs.modal', function () {
                $(".text-danger").remove();
                $(this).find('form').trigger('reset');
            })

            $('#EditModal').on('hidden.bs.modal', function () {
                $(".text-danger").remove();
                $(this).find('form').trigger('reset');
            })            
        // populate modal
            var id = $(this).data('id');
            var url = $(this).attr('href');
            var page = $('#page_hidden').val();
            var filter = $('#filter').val();
            var tanggal_mulai = $('#start_date').val();        
            var tanggal_berakhir = $('#end_date').val();  
            $.get(url, function (data) {
                    // success data
                    console.log(data);
                    // untuk pagination
                    $('#page').val(page);
                    $('#query_delete').val(filter);
                    $('#tanggal_mulai_delete').val(tanggal_mulai);
                    $('#tanggal_berakhir_delete').val(tanggal_berakhir);
                    // akhir pagination
                    $('#id').val(id);
                    $('#nama_delete').html(data.name);
                    $('#nomor_delete').html(data.nomor_surat);
                    $('#tanggal_delete').html(data.tanggal);
                    $('#pembuat_delete').html(data.submitted_by);
                    $('#DeleteModal').modal('show');
                }) 
            })
        //end populate modal
        
        // sent http request
        $('#delete-surat-btn').one('click', function (e) {
    
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
                    url     : form.attr('action') + "/" +  $('#id').val() + '?page=' + $('#page').val() + '&filter=' + $('#query_delete').val() + '&start_date=' + $('#tanggal_mulai_delete').val() + '&end_date=' + $('#tanggal_berakhir_delete').val(),
                    type    : 'POST',
                    data    : form.serialize(),
                    // timeout : 200,
                    success : function ( json )
                    {
                        console.log(json);
                        $('#DeleteModal').modal('hide');
                        $(document.body).removeClass("modal-open");
                        $(".modal-backdrop").remove();
                        $('.surats').html(json);
                    },
                    error: function (json)
                    {
                        alert('gagal menghapus.');
                    },
                    complete: function (json){

                    }
                });
            });
        });
    });
    </script>