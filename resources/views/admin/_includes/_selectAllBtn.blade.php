<div class="custom-control custom-checkbox {{isset($class)?$class:''}}">
               <input id="{{$id??'records_all'}}" type="checkbox" class="custom-control-input" data-target="{{$target}}" data-toggle="select-all"/>
               <label for="{{$id??'records_all'}}" class="custom-control-label d-flex">{!! $title??'&nbsp;' !!}</label>
</div>
