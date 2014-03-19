/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

(function ( $ ) {
    'use strict';

    $(document).ready(function() {
        $('.btn-ajax').click(function(e) {
            e.preventDefault();

            $('#content-modal .modal-body').load($(this).attr('href'));
            $('#content-modal').modal('show');
        });
    });
})( jQuery );
