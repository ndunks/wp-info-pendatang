(function ($) {
    function marquee(a, b) {
        var width = b.width();
        var start_pos = a.width();
        var end_pos = -width;
        var timer = 0;
        var left = 0;

        function scroll() {
            if (timer) return;
            timer = setInterval(function () {
                if (left < end_pos) {
                    left = start_pos;
                }
                b.css('left', --left);
            }, 10);
        }

        b.css({
            'width': width,
            'left': left
        });
        scroll();
        b.mouseenter(function () {
            clearInterval(timer);
            timer = 0;
        });
        b.mouseleave(function () {
            scroll()
        });
    }

    $(document).ready(function () {
        if ($('.info-pendatang-ticker-text').length) {
            marquee($('.info-pendatang-ticker-text'), $('.info-pendatang-ticker-text a'));
        }
    })
})(jQuery);
