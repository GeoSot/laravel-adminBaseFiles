@php
    /**
     * @var App\Models\Media\Medium $model
     * @var string $class
     * @var string $wrapperClass
     * @var string $onclickAction
     *
 */
  $onclick = (is_null($onclickAction)) ? '' : 'onclick = "'.$onclickAction.'"'
@endphp

<div class="{{$wrapperClass}}" data-originalimage="{{$model->getUrl()}}" data-originalid="{{$model->getKey()}}" {!! $onclick !!} itemprop="video" itemscope
     itemtype="http://schema.org/VideoObject">
    <video itemprop="video" alt="" class="" src="{{$model->getUrl()}}"/>
    <meta itemprop="url" content="{{$model->getUrl()}}"/>
</div>
