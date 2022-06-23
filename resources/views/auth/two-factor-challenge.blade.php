@extends($packageVariables->get('siteLayout'))

@section('content')

    @component('baseAdmin::auth._card')
        @slot('title')
            @lang('baseAdmin::site/users/user.twoFa.challenge.title')
        @endslot

        {!! form($form) !!}

        <div class="text-right">
            <button class="btn btn-link btn-sm" onclick="displayRecoveryField()">
                @lang('baseAdmin::site/users/user.twoFa.challenge.useSecret')
            </button>
        </div>
    @endcomponent

@endsection

@push('scripts')
    <script>
        const displayRecoveryField = () => {
            const recoveryWrapper = document.querySelector('[data-js-code="recovery"]')
            const codeWrapper = document.querySelector('[data-js-code="default"]')

            recoveryWrapper.toggleAttribute("hidden");
            codeWrapper.toggleAttribute("hidden");
            recoveryWrapper.querySelector('input').value = ''
            codeWrapper.querySelector('input').value = ''
        }
    </script>
@endpush
