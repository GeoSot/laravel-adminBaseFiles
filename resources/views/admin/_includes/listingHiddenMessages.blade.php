@push('modals')
    <!--listingHiddenMessages-->
    <div class="js-translatedWords text-hide" hidden>
     @php($lang=$viewVals->get('baseLang').'.listMessages')
        <span id="no_record_selected"> @lang($packageVariables->get('nameSpace').$lang.'.noRecordSelected')</span>
    <span id="no_record_selected_msg"> @lang($packageVariables->get('nameSpace').$lang.'.noRecordSelected_msg')</span>
    <span id="confirm_delete"> @lang($packageVariables->get('nameSpace').$lang.'.confirmDelete')</span>
    <span id="confirm_delete_msg"> @lang($packageVariables->get('nameSpace').$lang.'.confirmDelete_msg')</span>
    <span id="confirm_restore"> @lang($packageVariables->get('nameSpace').$lang.'.confirmRestore')</span>
    <span id="confirm_restore_msg"> @lang($packageVariables->get('nameSpace').$lang.'.confirmRestore_msg')</span>
    <span id="confirm_force_delete"> @lang($packageVariables->get('nameSpace').$lang.'.confirmForceDelete')</span>
    <span id="confirm_force_delete_msg"> @lang($packageVariables->get('nameSpace').$lang.'.confirmForceDelete_msg')</span>
 </div>
    <!--listingHiddenMessages END-->
@endpush
