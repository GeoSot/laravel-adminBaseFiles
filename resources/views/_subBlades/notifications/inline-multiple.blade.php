<div class="alert alert-danger my-2  alert-dismissible fade show" role="alert">
    <button type="button" class="btn-close close" data-bs-dismiss="alert" data-dismiss="alert" aria-label="Close">
        <span class="visually-hidden" aria-hidden="true">&times;</span>
    </button>
    <ul class="list-unstyled small">
        @foreach ($errors as $error)
            <li>{!! $error !!}</li>
        @endforeach
    </ul>
</div>
