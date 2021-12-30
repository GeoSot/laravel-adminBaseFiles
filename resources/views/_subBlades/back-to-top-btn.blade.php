<button class="btn btn-primary p-1 c-backToTop shadow-lg position-fixed opacity-75" data-pixels-scroll="{{$pixelsScroll??100}}">
    <i class="{{$iconClass ?? 'fas fa-angle-up fa-2x'}}  " aria-hidden="true"></i>
</button>


@push('scripts')
    <script defer data-comment="c-backToTop">
        (function () {
            let $link = document.querySelector('.c-backToTop');
            document.addEventListener('scroll', function (e) {
                let amountScrolled = $link.getAttribute('data-pixels-scroll');
                $link.classList.toggle('show', document.documentElement.scrollTop > parseInt(amountScrolled))
            });

            $link.addEventListener('click', function () {
                document.documentElement.scrollTo({ top: 0, behavior: 'smooth' })
            });
        })();
    </script>

    <style data-comment="c-backToTop">
        .c-backToTop {
            outline: none;
            visibility: hidden;
            width: 40px;
            height: 40px;
            transition: transform .8s ease, visibility .8s ease;
            transform: translateY(calc(60px + 40px));
            -webkit-transform: translateY(calc(60px + 40px));
            z-index: 9999;
            right: 20px;
            bottom: 40px;
            /*box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);*/
        }

        .c-backToTop.show {
            visibility: visible;
            transform: translateY(0);
            -webkit-transform: translateY(0);
        }
    </style>
@endpush
