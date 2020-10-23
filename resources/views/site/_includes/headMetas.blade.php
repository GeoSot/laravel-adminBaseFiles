<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
{{--<meta name="robots" content="index,follow"><!-- All Search Engines -->--}}
<meta name="robots" content="noindex, nofollow">
<!-- Tells Google not to provide a translation for this page -->
<meta name="google" content="notranslate">

{{--<meta name="theme-color" content="#309CC0">--}}

@php
    /**
     * @var \Illuminate\View\Factory $__env
     */

        $title=  ( isset($viewVals) and $viewVals->has('modelLang'))? __($packageVariables->get('nameSpace').$viewVals->get('modelLang').'.general.menuTitle'): __($packageVariables->get('nameSpace').'site/generic.app.title');
        $pushedTitle=$__env->getSection('documentTitle', $title);
@endphp

<title>{!! strip_tags( $pushedTitle) !!} | @lang($packageVariables->get('nameSpace').'site/app.title')</title>

<!--Tricks to Get Some Resources Quicker-->
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
<!--Tricks END-->

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<script type="text/javascript">
    window.Laravel =@json([
		'csrfToken' => csrf_token(),
		'debug' => config('app.debug'),
	])
</script>
