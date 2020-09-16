@php
    /**
     * @var App\Models\Media\Medium $model
     * @var \Illuminate\Support\Collection $options
 *
 */

  $onclick = ($options->get('onclickAction')) ? '' : 'onclick = "'.$options->get('onclickAction').'"'
@endphp

<figure class="{{ $options->get('wrapperClass')}}"
        data-originalimage="{{$model->getUrl()}}"
        data-originalid="{{$model->getKey()}}"
        itemprop="image" itemscope
        itemtype="http://schema.org/ImageObject"
    {!! $options->get('onclick') !!} >

    <img itemprop="image" alt="{{$model->alt_attribute?:$model->filename}}" class="img-responsive img-fluid js-lazy {{ $options->get('class')}}"
         src="{{App\Models\Media\Medium::getDummyImageUrl()}}"
         data-src="{{$model->getUrl()}}"/>
    <meta itemprop="url" content="{{$model->getUrl()}}"/>

</figure>
