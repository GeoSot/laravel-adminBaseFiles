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

<figure class="{{$wrapperClass}}" data-originalimage="{{$model->getUrl()}}" data-originalid="{{$model->getKey()}}" {!! $onclick !!} itemprop="image" itemscope
        itemtype="http://schema.org/ImageObject">
    <img itemprop="image" src="{{App\Models\Media\Medium::getDummyImageUrl()}}" alt="" class="img-responsive img-fluid js-lazy {{$class}}"
         data-src="{{$model->getUrl()}}"/>
    <meta itemprop="url" content="{{$model->getUrl()}}"/>
</figure>
