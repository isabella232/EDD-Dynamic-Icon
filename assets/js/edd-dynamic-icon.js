/*global jQuery, window, document, edd_dynamic_icon_vars, Favico*/
jQuery(document).ready(function ($) {
    'use strict';

    var favicon;

    favicon = new Favico({
        bgColor: edd_dynamic_icon_vars.background,
        textColor: edd_dynamic_icon_vars.color,
        fontStyle: edd_dynamic_icon_vars.style,
        type: edd_dynamic_icon_vars.shape,
        animation: edd_dynamic_icon_vars.animation
    });

    favicon.badge(edd_dynamic_icon_vars.count);

    jQuery('body').on('click.eddAddToCart', '.edd-add-to-cart', function () {
        if (typeof window.count === 'undefined') {
            window.count = parseInt(edd_dynamic_icon_vars.count, 10) + 1;
        } else {
            window.count = window.count + 1;
        }

        favicon.badge(window.count);
    });
});
