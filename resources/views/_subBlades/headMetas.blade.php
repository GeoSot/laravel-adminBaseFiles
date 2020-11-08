<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Tells Google not to provide a translation for this page -->
<meta name="google" content="notranslate">
@php
    $nameSpace=$packageVariables->get('nameSpace');
        $title=  ( isset($viewVals) and $viewVals->has('modelLang'))? __($nameSpace.$viewVals->get('modelLang').'.general.menuTitle'): __($nameSpace.'admin/generic.menu.dashboard');
        $pushedTitle=$__env->getSection('documentTitle', $title)
@endphp

<title>{!! $pushedTitle !!} | @lang($nameSpace.'admin/app.title')</title>

<!--Tricks to Get Some Resources Quicker-->
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
<!--Tricks END-->

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}"/>

@php
    $js = [
      'csrfToken' => csrf_token(),
      'debug' => config('app.debug'),
      'uppy' => [
          'metaFields' => [
              ['id' => 'name', 'name' => 'Name', 'placeholder' => 'file name'],
              ['id' => 'caption', 'name' => 'Caption', 'placeholder' => 'describe what the image is about'],
              ['id' => 'keywords', 'name' => 'Keywords',]
          ],
          'endPoint' => route('admin.media.tusUpload'),
          'restrictions' => [
              'maxFileSize' => 100000000,
              'maxNumberOfFiles' => 10,
              'minNumberOfFiles' => 1,
              'allowedFileTypes' => ['image/*', 'video/*']
          ],
      ],
  ]
@endphp
<script type="text/javascript">
    window.Laravel =@json($js)
</script>
