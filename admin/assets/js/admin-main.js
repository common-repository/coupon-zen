;jQuery(document).ready(function($){
    $('.couponzen_color-picker').wpColorPicker();

    const  couponzenAutoCoupon = function(elem) {
        if (elem.is(":checked")) {
            $("#couponzen_coupon_text").attr("disabled", "disabled");
        } else {
            $("#couponzen_coupon_text").removeAttr("disabled");
        }
    }
    
    couponzenAutoCoupon($("#couponzen_autoCoupon"))
    $("#couponzen_autoCoupon").click(function () {
        couponzenAutoCoupon($(this))
    });
});





