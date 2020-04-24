(function ($) {
    function marquee(a, b) {
        var width = b.width();
        var start_pos = a.width();
        var end_pos = -width;

        function scroll() {
            if (b.position().left <= -width) {
                b.css('left', start_pos);
                scroll();
            }
            else {
                time = 15000;
                b.animate({
                    'left': -width
                }, time, 'linear', function () {
                    scroll();
                });
            }
        }

        b.css({
            'width': width,
            'left': 0
        });
        scroll(a, b);
        b.mouseenter(function () {     // Remove these lines
            b.stop();                 //
            b.clearQueue();           // if you don't want
        });                           //
        b.mouseleave(function () {     // marquee to pause
            scroll(a, b);             //
        });                           // on mouse over
    }

    $(document).ready(function () {
        if ($('.info-pendatang-ticker-text').length) {
            marquee($('.info-pendatang-ticker-text'), $('.info-pendatang-ticker-text a'));
        }
    })
})(jQuery);
