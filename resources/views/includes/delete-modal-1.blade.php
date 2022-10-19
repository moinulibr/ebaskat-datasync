
{{---delete product modal----}}
<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <p class="text-center">{{ __('You are about to delete this ') }} {{$type??"item"}}.</p>
                <p class="text-center">{{ __('Do you want to proceed?') }}</p>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a class="btn btn-danger btn-submit">{{ __('Delete') }}</a>
            </div>

        </div>
    </div>
</div>
{{---delete product modal----}}


@push('scripts')
    {{---delete product modal----}}
    <script>
        $('#delete_modal').on('show.bs.modal', function (e) {
            $(this).find('.btn-submit').attr('href', $(e.relatedTarget).data('href'));
        });

        $('#delete_modal').on('click', '.btn-submit', function(e) {
            e.preventDefault();
            $.notify("Deleting  {{$type ?? 'item'}}...", "info");
            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                success: function(data) {
                    $('#delete_modal').modal('hide');
                    location.reload();

                    if (typeof data === 'string' || data instanceof String)
                    {
                        $.notify(data, "success");    
                    }
                    else
                    {
                        if(data.status == 'error')
                        {
                            $.notify(data.mgs, "error");
                        }
                    }
                },
                error: function (e) {
                    $.notify('Error Occur', "error");
                    $('#delete_modal').modal('hide');
                }
            });
        });
    </script>
    {{---delete product modal----}}
@endpush
