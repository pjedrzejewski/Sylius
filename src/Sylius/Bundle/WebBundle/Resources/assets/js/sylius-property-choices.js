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

    $(document).ready(function() {
        toogleChoices($('#sylius_property_type').val());

        $('#sylius_property_type').change(function (e) {
            toogleChoices($(this).val());
        });
    });

    function toogleChoices(value)
    {
       if (value === 'choice') {
           $('#property-choices-container').show();
       } else {
           $('#property-choices-container').hide();
       }
    }
})(jQuery);
