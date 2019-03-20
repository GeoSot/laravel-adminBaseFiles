@foreach($logs as $key => $log)
    <tr>
                  @if ($standardFormat)
            <td class="text-nowrap text-{{{$log['level_class']}}}">
                          <span class="fa fa-{{{$log['level_img']}}}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                      </td>
            <td class="text">{{$log['context']}}</td>
        @endif
        <td class="date">{{$log['date']}}</td>
                      <td class="text">
                          <div class="d-flex align-items-start">
                              <div>
                                  @if( preg_match_all('/{"([^}]+)\"}/',$log['text'], $matches))
                                      <code>{{{str_replace($matches[0],'',$log['text'])}}}</code>
                                      <div class="text-muted">-------</div>
                                      <code class=" d-block">{!! implode(PHP_EOL,$matches[0]) !!}</code>
                                  @else
                                      <code>{{{$log['text']}}}</code>
                                  @endif
                              </div>
                              @if ($log['stack'])
                                  <button type="button" class="expand btn btn-outline-dark btn-sm mb-2 ml-auto" data-toggle="collapse" data-target="#collapse_{{{$key}}}"
                                          aria-expanded="false" aria-controls="collapse_{{{$key}}}">
                                  <span class="fa fa-search"></span>
                                  </button>
                              @endif
                              @if (isset($log['in_file']))
                                  <br/>{{{$log['in_file']}}}
                              @endif

                          </div>
                      </td>
              </tr>
    @if ($log['stack'])
        <tr>
                      <td colspan="100%" class="p-0">
                          <div class="collapse p-2 bg-light" id="collapse_{{{$key}}}">
                                    @foreach(preg_split('/\r\n|\r|\n/',$log['stack']) as $block)
                                  <code class=" d-block">{{$block}}</code>
                              @endforeach
                          </div>
                      </td>
                  </tr>
    @endif
@endforeach
