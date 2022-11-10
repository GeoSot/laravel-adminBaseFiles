@php use GeoSot\BaseAdmin\App\Models\Setting; @endphp
@php($settings=Setting::where($scope??[])->get()->map(fn(Setting  $record)=>$record->getShortHtml()->render()))
@if($settings->isNotEmpty())
    <div class="mb-2">
        <button class="btn btn-secondary  text-left" type="button" data-target="#settings-collapse"
                data-toggle="collapse" aria-haspopup="true" aria-expanded="false">
            Related Settings
        </button>
        <div class="collapse" id="settings-collapse" style="right: 0; z-index: 10000">
            <div class="card">
                <div class="card-body small"> {!! $settings->implode("") !!} </div>
            </div>
        </div>
    </div>
@endif
