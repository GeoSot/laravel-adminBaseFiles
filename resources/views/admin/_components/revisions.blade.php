@php
    use Illuminate\Support\Collection;
  /**
  * @var Collection $viewVals
  */
          $btnsLang=$viewVals->get('baseLang').'.button';
          $revLang= $viewVals->get('baseLang').'.revisions';
          $baseRoute=$viewVals->get('baseRoute');
          $allowToHandle=$viewVals->get('record')?$viewVals->get('record')->allowedToHandle():true;
          $record=$viewVals->get('record');
@endphp


@if($viewVals->get('options')->get('modelIsRevisionable') && auth()->user()->isAbleTo('admin.edit-'.$modelClass) && $allowToHandle && $record)
    <div class="mr-auto">
        @component($packageVariables->get('blades').'_subBlades._components.modal',['id'=>'revisionsModal','animation'=>'','dialogClass' =>'modal-xl'] )
            @slot('triggerBtn')
                <a href="#" data-toggle="modal" data-target="#revisionsModal" role="button" class="btn btn-outline-primary btn-sm">
                    @lang("{$btnsLang}.revisions")
                </a>
            @endslot

            @slot('title')
                @lang($revLang.'.title')
            @endslot
            @slot('footer')
                @if( auth()->user()->isAbleTo('admin.restore-'.$modelClass) && $record->revisionHistory->isNotEmpty())
                    <form class="js-revisionsClearForm" action="{{route('admin.restore.clear',$record->revisionHistory->first())}}" method="POST">
                        @csrf
                        <button class="btn btn-outline-danger" type="submit">
                            <span class="">@lang($btnsLang.'.clearHistory')</span>
                        </button>
                    </form>
                @endif
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang($btnsLang.'.close')</button>
            @endslot


            @if($record->revisionHistory->isNotEmpty() )
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-sm">
                        <thead>
                        <tr class="border-bottom">
                            <th scope="col">@lang($revLang.'.label')</th>
                            <th scope="col">@lang($revLang.'.newValue')</th>
                            <th scope="col">@lang($revLang.'.oldValue')</th>
                            <th scope="col">@lang($revLang.'.user')</th>
                            <th scope="col">@lang($revLang.'.changedAt')</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($record->revisionHistory as $history )
                            <tr class="small border-bottom">
                                <td>@lang($viewVals->get('modelLang').'.fields.'.$history->fieldName())</td>
                                <td>{!! $history->newValue() !!}</td>
                                <td class="text-muted">{!! $history->oldValue() !!} </td>
                                <td>{!! $history->userResponsible()->frontConfigs->getAdminLink('fullName')  !!}</td>
                                <td>{{$history->created_at->format('d-m-Y H:i:s')}}</td>
                                <td>
                                    @if( auth()->user()->isAbleTo('admin.restore-'.$modelClass))
                                        <form class="js-revisionsForm" action="{{route('admin.restore',$history)}}" method="POST">
                                            @csrf
                                            <button class="btn btn-outline-primary btn-sm" type="submit">
                                                <span class="">@lang($revLang.'.restore')</span>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center">@lang($revLang.'.noHistoryRecords')</div>
            @endif

        @endcomponent
    </div>



    @push('scripts')
        <script defer data-comment="messagesNewFormFormAjax ">

            jsHelper.jQuery.execute(src => {
                BaseAdmin.forms.ajaxifyFormOnModal("form.js-revisionsForm", '#revisionsModal', '#revisionsModal, #mainForm')
            })
        </script>
    @endpush

@endif
