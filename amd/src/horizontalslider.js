define(['jquery', './flexslider'], function($, flex) {
    'use strict';

    return {
        init: function($element, properties) {

            $element.flexslider(properties);
            /* $(".container.slidewrap").on('transitionend', function() {
                var slider1 = $('#main-slider').data('flexslider');
                slider1.resize();
            }) */
        }
    };

});
