/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
(function ($) {
    'use strict';

    var mobileView = 992;

    function adjustSidebar() {
        if (window.innerWidth >= mobileView) {
            if ($.cookie('toggle') === undefined) {
                $('#page-wrapper').addClass('active');
            } else {
                if($.cookie('toggle') == 'true') {
                    $('#page-wrapper').addClass('active');
                } else {
                    $('#page-wrapper').removeClass('active');
                }
            }
        } else {
            $('#page-wrapper').removeClass('active');
        }
    };

    $(window).resize(function() {
        adjustSidebar();
    });

    $(document).ready(function() {
        adjustSidebar();

        $('#sidebar-toggle').click(function(e) {
            e.preventDefault();
            $('#page-wrapper').toggleClass('active');

            $.cookie('toggle', $('#page-wrapper').hasClass('active'));
        });

        $('.dropdown-toggle').dropdown()
        $('[rel="tooltip"]').tooltip();
    });
})(jQuery);
