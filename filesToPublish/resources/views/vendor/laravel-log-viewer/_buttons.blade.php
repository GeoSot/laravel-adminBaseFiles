<section class="button_actions card ">
    <div class=" card-body  py-2 d-flex ">

        @if($current_file)

            <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}"
               role="button" class="btn  btn-primary  m-1">
                <span class="btn-label"><i class="fas fa-download"></i></span>
                <span class="btn_text">Download file</span>
            </a>
            @if( auth()->user()->isAbleTo('admin.delete-log'))
                <button role="button" class="btn  btn-info  m-1" data-toggle="logAction" data-title="Clean Log File"
                        data-href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                    <span class="btn-label"><i class="fas fa-refresh"></i></span>
                    <span class="btn_text">Clean file</span>
                </button>

                <button id="delete-log" role="button" class="btn  btn-warning new_btn m-1" data-toggle="logAction" data-title="Delete File"
                        data-href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                    <span class="btn-label"><i class="far fa-trash-alt"></i></span>
                    <span class="btn_text">Delete file</span>
                </button>
                @if(count($files) > 1)
                    <button data-toggle="logAction" data-title="Delete all log files" role="button" class="btn  btn-warning  m-1"
                            data-href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                        <span class="btn-label"><i class="far fa-trash-alt"></i></span>
                        <span class="btn_text">Delete all files</span>
                    </button>
                @endif
            @endif
        @endif
    </div>
</section>

@push('scripts')

    <script defer data-comment="logActions">
        jsHelper.jQuery.execute(() => {
            $('[data-toggle="logAction"]').click(function (e) {
                let $btn = $(this);
                Swal.fire({
                    title: $btn.data('title'),
                    text: "Are you sure?",
                    icon: 'warning',
                    // dangerMode : true,
                }).then(function (continueAction) {
                    if (continueAction.isConfirmed) {
                        location.href = $btn.data('href');
                    }
                });
            });
        });
    </script>

@endpush
