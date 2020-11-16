<button id="return-to-top-btn" type="button" class="btn btn-info btn-sm  shadow-lg  font-weight-light " data-pixels-scroll="200">
    <i class="fas fa-angle-up fa-2x  " aria-hidden="true"></i>
</button>


@push('scripts')
    <style data-comment="return to top button">
        #return-to-top-btn {
            outline: none;
            display: none;
            position: fixed;
            z-index: 9999;
            right: 15px;
            bottom: 20px;
        }


    </style>
@endpush

@push('scripts')
    <script defer data-comment="return to top button">


        jsHelper.jQuery.execute(($) => {
            let $link = $('#return-to-top-btn');
            $(window).scroll(function () {
                let amountScrolled = $link.attr('data-pixels-scroll');
                ($(window).scrollTop() > amountScrolled) ? $link.fadeIn('slow') : $link.fadeOut('slow');
            });

            $link.click(function () {
                $('html, body').animate({
                    scrollTop: 0
                }, 1000);
                return false;
            });
        });

    </script>
@endpush

