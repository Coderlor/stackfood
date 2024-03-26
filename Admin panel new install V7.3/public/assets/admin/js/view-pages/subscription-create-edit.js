"use strict";
$('.show_order_input').click(function() {
    $('#max_o').removeAttr("hidden");
});

$('.hide_order_input').click(function() {
    $('#max_o').attr("hidden","true");
    $('#max_o').val(null).trigger('change');
});

$('.show_product_input').click(function() {
    $('#max_p').removeAttr("hidden");
});

$('.hide_product_input').click(function() {
    $('#max_p').attr("hidden","true");
    $('#max_p').val(null).trigger('change');
});

$('#select-all').on('change', function() {
    if (this.checked === true) {
        $('.check--item-wrapper .check-item .form-check-input').attr('checked', true)
    } else {
        $('.check--item-wrapper .check-item .form-check-input').attr('checked', false)
    }
});

$('#reset_btn').click(function() {
    location.reload(true);
});

$(document).ready(function(){
    $('#show_button_1').click(function(){
        $('#show_1').toggle();
        $('#show_button_1').hide();
    });
});
$(document).ready(function(){
    $('#show_button_2').click(function(){
        $('#show_2').toggle();
        $('#show_button_2').hide();
    });
});
