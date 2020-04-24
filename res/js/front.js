(function ($) {
    function marquee(a, b) {
        var width = $(b).width();
        var start_pos = $(a).width();
        var end_pos = -width;
        console.log(start_pos, end_pos, width);
        var timer = 0;
        var left = 0;

        function scroll() {
            if (timer) return;
            timer = setInterval(function () {
                if (left < end_pos) {
                    left = start_pos;
                }
                b.style.left = --left + "px"
            }, 10);
        }
        b.style.width = width + "px"
        b.style.left = left + "px"
        scroll();
        a.onmouseenter = function () {
            clearInterval(timer);
            timer = 0;
        };
        a.onmouseleave = function () {
            scroll()
        };
    }

    $(document).ready(function () {
        if ($('.info-pendatang-ticker-text').length) {
            marquee($('.info-pendatang-ticker-text')[0], $('.info-pendatang-ticker-text a')[0]);
        }
    })
})(jQuery);
