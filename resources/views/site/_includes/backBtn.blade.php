@if(Route::has($routeName))
    <div class="my-5 text-right">
        <a href="{{route($routeName)}}" class=" btn btn-outline-dark"> @lang($packageVariables->get('nameSpace').$viewVals->get('modelLang').'.buttons.back' )</a>
    </div>
@endif
