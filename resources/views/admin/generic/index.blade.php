@extends($packageVariables->get('adminLayout'))


@section('content')

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
