@extends($packageVariables->get('adminLayout'))

@php
    use Illuminate\Support\Collection;
    /**
    * @var Collection $viewVals
    */
    $action=$viewVals->get('action');
    $btnsLang=$viewVals->get('baseLang').'.button';
    $options=$viewVals->get('options')->get("editActions");
    $modelClass=lcfirst($viewVals->get('modelClassShort'));
    $allowToHandle=$viewVals->get('record')?$viewVals->get('record')->allowedToHandle():true;

@endphp

@section('content')

    @component($packageVariables->get('blades').'admin._components.listingButtons',['viewVals'=>$viewVals, ]  )
        @slot('afterLeft')
            @if(in_array('save',$viewVals->get('options')->get('editActions')) and auth()->user()->isAbleTo('admin.update-'.$modelClass) and $allowToHandle)
                <button id="save" class="btn btn-success  my-1" data-value="save" onclick="document.getElementById('permissionsForm').submit();">
                    <span class="btn-label"><i class="fa fa-floppy-o"></i></span>
                    <span class="btn_label"> @lang($btnsLang.'.save')</span>
                </button>
            @endif
        @endslot
    @endcomponent
    @php($form=$viewVals->get('form'))
    {!!  form_start($form) !!}

    <div class=" card row">
        <div class=" card-body col-12">
              <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm table-bordered ">
                         <thead class="bg-info text-white ">
                             @include($packageVariables->get('blades').'admin.users.userPermissions._listingTableHead')
                         </thead>
                        <tbody>
                            @include($packageVariables->get('blades').'admin.users.userPermissions._listingTableBody')
                        </tbody>
                    </table>
              </div>
        </div>
    </div>
    {!!form_end($form, true); !!}
@endsection
