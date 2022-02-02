@extends($packageVariables->get('adminLayout'))


@section('content')
    <div class="card row mb-2">
        <div class="pt-2">
            <div class="d-flex justify-content-center mb-2">
                <button class="btn btn-dark" data-toggle="collapse" data-target="#collapse-uploader">Open Uploader</button>
            </div>
            <div class="collapse my-2" id="collapse-uploader">
                <div class="d-flex justify-content-center">
                    <uppy :is-shown="true" :options="{{\Illuminate\Support\Js::from(\GeoSot\BaseAdmin\Helpers\Uploads::getUppyOptions(\App\Models\Media\Medium::TYPE_ALL))}}">
                    </uppy>
                </div>
            </div>
        </div>
    </div>
    @component($packageVariables->get('blades').'admin._components.listDataParsing',['viewVals'=>$viewVals, ]  )
        @slot('tableData')
            @foreach($viewVals->get('records') as $record)
                <tr>
                    @include($packageVariables->get('blades').'admin._includes._listDataParsing_helper', ['parse'=>'firstCheckBox'])
                    @foreach($listable=$viewVals->get('extraValues')->get('listable') as $listName)
                        @include($packageVariables->get('blades').'admin._includes._listDataParsing_helper', ['parse'=>'fields'])
                    @endforeach
                </tr>
            @endforeach
        @endslot
    @endcomponent
@endsection


@push('scripts')
    <script>
        document.addEventListener('uppy-complete', () => {
           BaseAdmin.ajaxLoadWrappers('#recordsList')
        })
    </script>
@endpush
