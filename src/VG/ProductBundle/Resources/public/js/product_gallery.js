$(document).ready(function () {
    $("#list_product_image_middle").on("click", "a", function () {
        $(this).addClass('no_display');
        $(this).next().removeClass('no_display');
        if ($(this).next().length == 0) {
            $("#list_product_image_middle a:first-child").removeClass('no_display');
        }
    });
});
