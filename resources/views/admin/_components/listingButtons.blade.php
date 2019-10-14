@push('topBar')
    <!--com_listingButtons-->
    @php
        use Illuminate\Support\Collection;
        /**
        * @var Collection $viewVals
        */
        $btnsLang=$viewVals->get('baseLang').'.button';
   		$baseRoute=$viewVals->get('baseRoute');
   		$params=$viewVals->get('params');
   		 $options=$viewVals->get('options')->get('indexActions');
		$modelClass=lcfirst($viewVals->get('modelClassShort'));
    @endphp

    <!--com_listingButtons-->
    <section class="button_actions card js-buttonActions" data-script="_admin/indexPages.js">
        <div class=" card-body row py-2 flex-wrap">
            <div class="btns_left_area  col-xl-10 col-lg-9  col-12 flex-wrap">
                @isset($beforeLeft) {!!$beforeLeft !!} @endisset
                @if(in_array('create',$options) and auth()->user()->can('admin.create-'.$modelClass))
                    <a href="{{ route("{$baseRoute}.create") }}" class="btn  btn-primary new_btn m-1" role="button">
                        <span class="btn-label"><i class="fa fa-plus"></i></span>
                        <span class="btn_text"> @lang("{$btnsLang}.create")</span>
                    </a>
                @endif
                @if(in_array('enable',$options) and auth()->user()->can('admin.update-'.$modelClass))
                    <button id="enable-items" data-toggle="listing-actions" data-method="POST" data-keyword="enable" class="btn btn-light  m-1"
                            type="button" data-url="{{ route("{$baseRoute}.change.status") }}">
                        <span class="btn-label"><i class="fa fa-check-circle-o"></i></span>
                        <span class="btn_text"> @lang("{$btnsLang}.enable")</span>
                    </button>
                @endif
                @if(in_array('disable',$options) and auth()->user()->can('admin.update-'.$modelClass))
                    <button id="disable-items" data-toggle="listing-actions" data-method="POST" data-keyword="disable" class="btn btn-light  m-1"
                            type="button" data-url="{{ route("{$baseRoute}.change.status") }}">
                        <span class="btn-label"><i class="fa fa-ban"></i></span>
                        <span class="btn_text"> @lang("{$btnsLang}.disable")</span>
                    </button>
                @endif
                @if(in_array('delete',$options) and $params->get('trashed')==0 and auth()->user()->can('admin.delete-'.$modelClass))
                    <button id="delete-items" data-toggle="listing-actions" data-method="DELETE" data-keyword="delete" class="btn  btn-danger m-1"
                            type="button" data-url="{{ route("{$baseRoute}.delete") }}">
                        <span class="btn-label"><i class="fa fa-trash-o"></i></span>
                        <span class="btn_text"> @lang("{$btnsLang}.delete")</span>
                    </button>
                @endif
                @if($params->get('trashed')==1  and in_array('restore',$options)  and auth()->user()->can('admin.restore-'.$modelClass))
                    <button id="restore-items" data-toggle="listing-actions" data-keyword="restore" data-method="POST" class="btn  btn-success m-1"
                            type="button" data-url="{{ route("{$baseRoute}.restore") }}">
                        <span class="btn-label"><i class="fa fa-undo"></i></span>
                        <span class="btn_text"> @lang("{$btnsLang}.restore")</span>
                    </button>
                @endif
                @if($viewVals->get('options')->get('modelHasSoftDeletes') and in_array('forceDelete',$options) and $params->get('trashed')==1  and auth()->user()->can('admin.forceDelete-'.$modelClass))
                    <button id="forceDelete-items" data-toggle="listing-actions" data-keyword="force_delete" data-method="DELETE" class="btn  m-1 btn-danger "
                            type="button" data-url="{{ route("{$baseRoute}.force.delete") }}">
                        <span class="btn-label"><i class="fa fa-eraser"></i></span>
                        <span class="btn_text"> @lang("{$btnsLang}.permDelete")</span>
                    </button>
                @endif
                @isset($afterLeft) {!!$afterLeft !!} @endisset
            </div>
            <div class="btns_right_area  col-xl-2 col-lg-3 col-12  text-right">
                @isset($beforeRight) {!!$beforeRight !!} @endisset
                @if($viewVals->get('options')->get('modelHasSoftDeletes'))
                    <select class="form-control custom-select w-auto m-1" id="trashed" name="trashed" data-change="js-submit-form">
                        <option value="0" {!! ($params->get('trashed')==0) ? 'selected="selected"':'' !!}> @lang("{$btnsLang}.untrashed")</option>
                        <option value="1" {!! ($params->get('trashed')==1) ? 'selected="selected"':'' !!}> @lang("{$btnsLang}.trashed")</option>
                    </select>
                @endif
                @isset($afterRight) {!!$afterRight !!} @endisset
            </div>
        </div>
    </section>
    <!--com_listingButtons END-->
    @include($packageVariables->get('blades').'admin._includes.listingHiddenMessages')
@endpush
