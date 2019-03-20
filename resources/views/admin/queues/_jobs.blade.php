@foreach($record->get('records') as $job)
    @php
        $payload= json_decode($job->payload) ;
    @endphp
    <tr>
        <td>
            {{$job->id}}
        </td>
        <td>
            {!! $payload->displayName !!}
        </td>
        <td>
            {{$job->queue}}
        </td>
        <td>
            {{$job->attempts}}
        </td>
        <td>
            {{  empty($job->reserved_at)?'': \Carbon\Carbon::createFromTimestamp( $job->reserved_at)}}
        </td>
        <td>
        {{ empty($job->available_at)?'':\Carbon\Carbon::createFromTimestamp( $job->available_at)}}
        </td>
        <td>
        {{ empty($job->created_at)?'': \Carbon\Carbon::createFromTimestamp( $job->created_at)}}
        </td>
        <td>
        </td>
    </tr>

@endforeach
