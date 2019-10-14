@extends($packageVariables->get('adminLayout'))

@push('documentTitle') Laravel Log Viewer @endpush
@push('topBar')
    @include('vendor.laravel-log-viewer._buttons')
@endpush

@section('content')


    <div class="row flex-fill">
        <div class="flex-fill px-1 col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
            @include('vendor.laravel-log-viewer._sideBar')
        </div>
        <div class="col-12  col-sm-8 col-md-8 col-lg-9 col-xl-9 ">
            <div class="card">
                <div class="card-body">
                    @if ($logs === null)
                        <div class="h2">Log file >50M, please download it.</div>
                    @else
                        <div class="table-responsive ">
                            <table id="table-log" class="table table-hover table-sm" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
                                <thead class=" bg-info text-white">
                                    <tr>
                                        @if ($standardFormat)
                                            <th class="py-2">Level</th>
                                            <th class="py-2">Context</th>
                                            <th class="py-2">Date</th>
                                        @else
                                            <th class="py-2">Line number</th>
                                        @endif
                                        <th class="py-2">Content</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @include('vendor.laravel-log-viewer._tableBody')
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
