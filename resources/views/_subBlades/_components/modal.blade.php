@isset($triggerBtn)
    {!! $triggerBtn !!}
@endisset

@push('modals')
    <div class="modal {{ $animation ?? 'fade' }} " id="{{ $id ?? 'modal' }}"  tabindex="-1" role="dialog" aria-labelledby="..." aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered {{ $dialogClass ?? '' }}" role="document">
            <div class="modal-content {{ $class ?? '' }}">
                @isset($title)
                    <div class="modal-header">
                        <div class="modal-title h5">{{ $title }}</div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endisset

                <div class="modal-body">
                    {{ $slot }}
                </div>

                @isset($footer)
                    <div class="modal-footer">
                        {{ $footer }}
                    </div>
                @endisset
            </div>
        </div>
    </div>
@endpush
