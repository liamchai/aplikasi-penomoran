<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="delete_form" method="POST" action={{ action('AccessController@destroy', [$username, request('username')]) }}>
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus data ini?</p>
                        <table>
                            <tr>
                                <td>Akses </td>
                                <td> : </td>
                                <td><span id="username_delete"></span></td>
                            </tr>
                            <tr>
                                <td>URL </td>
                                <td>:</td>
                                <td> <span id="url_delete"></span></td>
                            </tr>
                            <tr>
                                <td>Departemen </td>
                                <td>:</td>
                                <td> <span id="departemen_delete"></span></td>
                            </tr>
                        </table>
                        <input type="hidden" id="delete_token"/>
                        <input type="hidden" id="id"/>
                        <input type="hidden" name="page" id="page_delete">
                        <input type="hidden" name="query" id="query_delete">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger" id="delete-access-btn">Hapus</button>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
    
    <script>
    $(document).ready(function () {
        $(document).on('click', '#delete-access', function (e) {
            e.preventDefault();
            
        // populate modal
            var id = $(this).data('id');
            var url = $(this).attr('href');
            var page = $('#page_hidden').val();
            var filter = $('#filter').val();
            $.get(url, function (data) {
                    // success data
                    $('#page_delete').val(page);
                    $('#query_delete').val(filter);
                    // console.log(id);
                    $('#id').val(id);
                    $('#username_delete').html(data.name);
                    $('#url_delete').html(data.url);
                    $('#departemen_delete').html(data.departemen);
                    $('#DeleteModal').modal('show');
                    
                }) 
            })
        //end populate modal
        
        // sent http request
        $('#delete-access-btn').one('click', function (e) {
    
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
                    url     : form.attr('action') + "/" +  $('#id').val() + '?page=' + $('#page_hidden').val() + '&filter=' + $('#filter').val() + '&sortby=' + $('#hidden_column_name').val() + '&sorttype=' + $('#hidden_sort_type').val() + '&show_data=' + $('#show_data').val(),
                    type    : 'POST',
                    data    : form.serialize(),
                    beforeSend : function(){
                        $('.container-fluid').addClass('block');
                    },
                    success : function ( json )
                    {
                        console.log(json);
                        $('#DeleteModal').modal('hide');
                        $(document.body).removeClass("modal-open");
                        $(".modal-backdrop").remove();
                        $('.access').html(json);
                        if (order_type == 'desc'){
                            $('#'+column_name+'_icon').html('<i class="fa fa-caret-down" aria-hidden="true"></i>');
                        }
                        if (order_type == 'asc'){
                            $('#'+column_name+'_icon').html('<i class="fa fa-caret-up" aria-hidden="true"></i>');
                        }
                    },
                    error: function (json)
                    {
                        console.log(json);
                    },
                    complete: function(){
                        $('.container-fluid').removeClass('block');
                    }
                });
            });
        });
    });
    </script>