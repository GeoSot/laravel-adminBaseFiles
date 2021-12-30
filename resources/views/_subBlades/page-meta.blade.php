@php
    /**
     * @var \App\Helpers\PageMeta $meta
     */

    $description = $meta->getDescription();
    $title = $meta->getTitle();
    $keywords = $meta->getKeywords();
    $image = $meta->getImage();
    $url = $meta->getUrl();
    $siteName = $meta->getSiteName();

@endphp
<title>{!! $title !!} | {{$siteName}}</title>

<link rel="canonical" href="{{$url}}" itemprop="url"/>

<!-- Schema.org markup for Google -->
<meta itemprop="name" content="{{$siteName}}">
<meta itemprop="description" content="{{$description}}">
<meta itemprop="image" content="{{$image}}">


<meta name="description" content="{{$description}}">
<meta name="keywords" content="{{$keywords}}">

<link rel="alternate" href="{{$url}}" hreflang="x-default"/>

<!-- Open Graph data -->
<meta property="og:description" content="{{$description}}"/>
<meta property="og:title" content="{{$title}}"/>
<meta property="og:image" content="{{$image}}"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="{{$url}}"/>
<meta property="og:site_name" content="{{$siteName}}"/>
<meta property="og:locale" content="{{App::getLocale()}}"/>


<!-- Twitter data -->
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:creator" content="@George Sotiropoulos">
<meta name="twitter:site" content="{{$siteName}}">
<meta name="twitter:title" content="{{$title}}">
<meta name="twitter:description" content="{{$description}}">
<meta name="twitter:image" content="{{$image}}">


<!-- Twitter data -->
@foreach($meta->getExtraMetaTags() as $name => $content)
    <meta name="{{$name}}" content="{{$content}}">
@endforeach
