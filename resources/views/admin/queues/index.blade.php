@extends($packageVariables->get('adminLayout'))

@includeIf("admin.queues._failed_jobs_actionsForAll")

@section('content')
    <div class="card ">
        <div class="card-body">

                @foreach( $viewVals->get('records') as  $index=>$record)
                <div class="h4"> @lang("{$viewVals->get('modelLang')}.general.{$index}")</div>
                <div class="table-responsive small">
                            <table class="table  table-hover  table-sm mb-5">
                                <thead class=" @if($index=='jobs') bg-primary @else bg-danger @endif text-white">
                                <tr>
                                    @foreach( $record->get('listable') as $name)
                                        <th scope="col"> @lang("{$viewVals->get('modelLang')}.fields.{$name}")</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @includeIf("admin.queues._{$index}")
                                </tbody>
                            </table>
                        </div>
            @endforeach

        </div>
    </div>
@endsection
