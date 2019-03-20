@component($packageVariables->get('blades').'admin._components.formCard',['show'=>false, 'title'=>__($packageVariables->get('nameSpace').$viewVals->get('modelLang').'.formTitles.usersOnRole'),'viewVals'=>$viewVals ])

    @if($viewVals->get('record') and $viewVals->get('record')->users->count())
        <ul>
	                    @foreach($viewVals->get('record')->users as $user)
                <li>
                                <a href="{!! route('admin.users.edit', $user) !!}">{{ $user->full_name}}</a>
                            </li>
            @endforeach
                     </ul>
    @else
        <div class="no_users"></div>
    @endif

@endcomponent
