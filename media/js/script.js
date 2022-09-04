jQuery(document).ready(function ($) {
    jQuery("#jform_name").on("keyup", function () {
        alert($(this).val());
    });
});