<div>
    <div class="modal fade" id="EditAccessModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="userCrudModal">Edit Akses <span id="username_edit_access"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST" id="edit_access_form" action={{ action('UserController@updateAccess', [$username, request('username')]) }}>
                @method('PATCH')
                <div class="modal-body">
                        {{-- <input type="hidden" name="username_edit_access" id="username_edit_access"> --}}
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
                        <input type="hidden" name="page" id="page_edit_access">
                        <input type="hidden" name="query" id="query_edit_access">
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
        var page = $('#page_hidden').val();
        var filter = $('#filter').val();
        var url = $(this).attr('href');
        $.get(url, function (data) {
                $('#username_edit_access').html(data.name);
                $('#page_edit_access').val(page);
                $('#query_edit_access').val(filter);
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
                url     : form.attr('action') + "/" +  $('#username_edit_access').html() + '?page=' + $('#page_edit_access').val() + '&filter=' + $('#query_edit_access').val(),
                type    : 'POST',
                data    : form.serialize(),
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
                }
            })
        })
    });
});
</script>