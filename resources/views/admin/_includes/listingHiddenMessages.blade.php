@push('modals')
    <!--listingHiddenMessages-->
    @php
        use Illuminate\Support\Collection;
        /**
        * @var Collection $viewVals
        * @var Collection $packageVariables
        */
    @endphp
    <div class="js-translatedWords text-hide" hidden>
        @php($lang=$packageVariables->get('nameSpace').$viewVals->get('baseLang').'.listMessages')
        <span id="no_record_selected"> @lang($lang.'.noRecordSelected')</span>
        <span id="no_record_selected_msg"> @lang($lang.'.noRecordSelected_msg')</span>
        <span id="confirm_delete"> @lang($lang.'.confirmDelete')</span>
        <span id="confirm_delete_msg"> @lang($lang.'.confirmDelete_msg')</span>
        <span id="confirm_restore"> @lang($lang.'.confirmRestore')</span>
        <span id="confirm_restore_msg"> @lang($lang.'.confirmRestore_msg')</span>
        <span id="confirm_force_delete"> @lang($lang.'.confirmForceDelete')</span>
        <span id="confirm_force_delete_msg"> @lang($lang.'.confirmForceDelete_msg')</span>
    </div>
    <!--listingHiddenMessages END-->
@endpush
