<div class="container">
    <div class="row justify-content-center">
        <div class=" col col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    @isset($title)
                        <div class="mb-4 h4 text-primary">{!! $title !!}</div>
                    @endisset
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>
