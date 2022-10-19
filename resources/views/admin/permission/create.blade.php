<!-- Modal -->
<div class="modal fade" id="permission_add_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Permission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="permission_form" action="{{ route('admin-permission-store') }}">
                @csrf

                <div class="modal-body">
                    <div class="form-group">
                        <label for="permission_name">Permission Name</label>
                        <input type="text" name="name" id="permission_name" class="form-control" placeholder="name"
                            required>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input id="is_special_check" class="form-check-input" type="checkbox" name="is_special" value="1"> Is Special
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="submit_btn" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        $('#permission_form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if ((data.errors)) {
                        $('.alert-danger').show();
                        $('.alert-danger ul').html('');
                        for (var error in data.errors) {
                            $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>');
                        }
                        $('.submit-loader').hide();
                        $(" .modal-content .modal-body .alert-danger").focus();
                        $('button.addProductSubmit-btn').prop('disabled', false);
                    } else {

                        table.ajax.reload();
                        $('.alert-success').show();
                        $('.alert-success p').text(data);
                        $('#permission_add_modal').modal('hide');
                        $('#permission_name').val("");
                        $("#is_special_check").prop("checked", false);
                    }
                }

            });
        });

    </script>
@endpush
