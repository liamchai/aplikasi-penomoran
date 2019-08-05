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
            $.get(url, function (data) {
                    // success data
                    console.log(data);
                    // untuk pagination
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
                var order_type = $('#hidden_sort_type').val();
                var column_name = $('#hidden_column_name').val();
                $.ajax({
                    url     : form.attr('action') + "/" + $('#id').val() + '?page=' + $('#page_hidden').val() + '&filter=' + $('#filter').val() + '&start_date=' + $('#start_date').val() + '&end_date=' + $('#end_date').val() + '&sortby=' + $('#hidden_column_name').val() + '&sorttype=' + $('#hidden_sort_type').val() + '&show_data=' + $('#show_data').val(),
                    type    : 'POST',
                    data    : form.serialize(),
                    beforeSend : function(){
                        $('.container-fluid').addClass('block');
                    },
                    // timeout : 200,
                    success : function ( json )
                    {
                        console.log(url);
                        $('#DeleteModal').modal('hide');
                        $(document.body).removeClass("modal-open");
                        $(".modal-backdrop").remove();
                        $('.surats').html(json);
                        if (order_type == 'desc'){
                            $('#'+column_name+'_icon').html('<i class="fa fa-caret-down" aria-hidden="true"></i>');
                        }
                        if (order_type == 'asc'){
                            $('#'+column_name+'_icon').html('<i class="fa fa-caret-up" aria-hidden="true"></i>');
                        }
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