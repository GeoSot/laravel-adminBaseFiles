<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-globe-europe fa-lg pr-1 "></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown_lang">
        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
            <a class=" dropdown-item @if(LaravelLocalization::getCurrentLocale() ==$localeCode) active @endif" rel="alternate" hreflang="{{ $localeCode }}"
               href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                {{ $properties['native'] }}
            </a>
        @endforeach
    </div>
</li>
