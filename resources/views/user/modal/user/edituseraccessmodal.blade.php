<div>
    <div class="modal fade" id="EditAccessModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="userCrudModal">Edit Akses <span id="username_edit_access"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" id="edit_access_form" action={{ action('UserController@updateAccess', [request('username'), request('username')]) }}>
                @method('PATCH')
                <div class="modal-body">
                        <input type="hidden" name="name_edit" id="name_edit">
                        <table class="table">
                            <tr>
                                <td></td>
                                <td><input type="checkbox" name="checkedAll" id="checkedAll">Check Semua</td>
                            </tr>
                            <tr class="items">
                                <td>Nama</td>
                                <td>Akses</td>
                            </tr>
                        </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="edit-user-access-btn">Edit Akses</button>
                </div>
            @csrf
            </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {

    $('#EditAccessModal').on('hidden.bs.modal', function () {
        $(".item").remove();
        $(this).find('form').trigger('reset');
    })
    // populate modal 
    $(document).on('click', '#edit-user-access', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = $(this).attr('href');
        $.get(url, function (data) {
                $('#username_edit_access').html(data.name);
                $('#name_edit').val(data.username);
                var count = data.count;
                var usergranted = data.usergranted;
                $.each(data.access, function (key, value){
                var checked = '';
                for (i = 0; i<count; i++){
                    if (usergranted[i] == value['id']){
                        checked = 'checked="checked"';
                    }
                }
                    $('.items').closest('.table')
                            .append('<tr class="item">' 
                                    + '<td><label for="label' + value['id'] + '" >' + value['name'] + '</label></td>'
                                    + '<td>' + '<input type="checkbox"' + checked + ' class="checkSingle" name="access[]" id="label' + value['id'] + '" value=' + value['id']  
                                    + '></td>'
                                    + '</tr>');
                });
                $("#checkedAll").change(function(){
                    if(this.checked){
                        $(".checkSingle").each(function(){
                            this.checked=true;
                        })              
                    }else{
                        $(".checkSingle").each(function(){
                            this.checked=false;
                        })              
                    }
                });

                $(".checkSingle").click(function () {
                    if ($(this).is(':checked')){
                    var isAllChecked = 0;
                    $(".checkSingle").each(function(){
                        if(!this.checked)
                        isAllChecked = 1;
                    })              
                    if(isAllChecked == 0){ $("#checkedAll").prop("checked", true); }     
                    }else {
                    $("#checkedAll").prop("checked", false);
                    }
                });
                $('#EditAccessModal').modal('show');

            }) 
        })
        //end populate modal

    // sent http request

    $('#edit-user-access-btn').one('click', function (e) {
        var form = $('#edit_access_form');
        form.submit(function(e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url     : "/" + $('#name_edit').val() + "/daftaruser/" +  $('#username_edit_access').html() + '/editaccess?page=' + $('#page_hidden').val() + '&filter=' + $('#filter').val() + '&sortby=' + $('#hidden_column_name').val() + '&sorttype=' + $('#hidden_sort_type').val() + '&show_data=' + $('#show_data').val(),
                type    : 'POST',
                data    : form.serialize(),
                beforeSend : function(){
                    $('.container-fluid').addClass('block');
                },
                success : function ( json ){
                    $('#EditAccessModal').modal('hide');
                    $(document.body).removeClass("modal-open");
                    $(".modal-backdrop").remove();
                    $('.users').html(json);
                },
                error   : function( json )
                {
                    console.log(this.url);
                    console.log(json);
                },
                complete: function(){
                    $('.container-fluid').removeClass('block');
                }
            })
        })
    });
});
</script>