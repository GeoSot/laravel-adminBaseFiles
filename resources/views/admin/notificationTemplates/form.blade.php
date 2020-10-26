@extends($packageVariables->get('adminLayout'))
@php
    use Illuminate\Support\Collection;
     /**
     * @var Collection $packageVariables
     * @var Collection $viewVals
     */
@endphp

@component($packageVariables->get('blades').'admin._components.createEditButtons',['viewVals'=>$viewVals]    )
@endcomponent

@section('content')
    @php($form=$viewVals->get('extraValues')->get('form'))

    {!!  form_start($form) !!}

    <div class="row">
        <div class=" col-12 mb-3 col-md-10 offset-md-1">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($packageVariables->get('nameSpace').$viewVals->get('baseLang').'.formTitles.first')] )
                {!! form_until($form,'group') !!}
                <hr class="my-5">
                {!! form_until($form,'language') !!}
                <hr class="my-5">
                <div class="row">
                    {!! form_until($form,'salutation') !!}
                </div>
            @endcomponent
        </div>
        <div class="col-md-6 offset-md-1 col-12 mb-3">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('modelLang').'.formTitles.developerChoices')]   )
                @if($viewVals->get('action')=='edit')
                    <div class="text-right">
                        <button data-toggle="enableDevFields" type="button" class="btn btn-secondary btn-sm"> @lang($viewVals->get('modelLang').'.buttons.enableDevFields')</button>
                    </div>
                @endif
                {!! form_rest($form) !!}
            @endcomponent
        </div>

    </div>

    {!!form_end($form, true); !!}
@endsection

@push('scripts')
    <script defer data-comment="enableDevFields">
        jsHelper.base.execute(() => {
            const $devFields = JSON.parse($('[name="dev_fields"]').val());
            $(document).on('click', '[data-toggle="enableDevFields"]', function (e) {
                $.each($devFields, function (i, name) {
                    let $el = $('[name="' + name + '"]').first();
                    if ($el.is('[disabled]')) {
                        $el.removeAttr('disabled');
                    } else {
                        $el.attr('disabled', true);
                    }
                })
            });
        });
    </script>

@endpush
