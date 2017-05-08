$.fn.paddingTop = function() {
    var widths = {
        top    : 0,
    };

    if ($(this).length > 0) {

        $.each($(this), function() {
            widths = {
                top    : Number($(this).css('padding-top').replace('px', ''))
            };
        });

    }

    return widths;
};


$(function() {
    var result  = $sample.paddingWidth(),
        $sample = $('.sample');

    console.log(result);
});