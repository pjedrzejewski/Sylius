(function($) {
  $.fn.extend({
    requireConfirmation: function() {
      return this.each(function() {
        return $(this).on('click', function(event) {
          event.preventDefault();

          var actionButton = $(this);

          if (actionButton.is('a')) {
            $('#confirmation-button').attr('href', actionButton.attr('href'));
          }
          if (actionButton.is('button')) {
            $('#confirmation-button').on('click', function(event) {
              event.preventDefault();

              return actionButton.closest('form').submit();
            });
          }

          return $('#confirmation-modal').modal('show');
        });
      });
    }
  });

  $(document).ready(function() {
    $('#sidebar')
        .first()
        .sidebar('attach events', '#sidebar-toggle', 'show')
    ;

    $('.ui.checkbox').checkbox();
    $('.ui.accordion').accordion();
    $('.link.ui.dropdown').dropdown({action: 'hide'});
    $('.ui.dropdown').dropdown();
    $('.ui.rating').rating('disable');
    $('.ui.tabular.menu .item').tab();
    $('.card .image').dimmer({on: 'hover'});
    $('.cart.button')
        .popup({
            popup: $('.cart.popup'),
            on: 'click',
        })
    ;

    $('.form button').on('click', function() {
      return $(this).closest('form').addClass('loading');
    });
    $('.message .close').on('click', function() {
      return $(this).closest('.message').transition('fade');
    });

    $('[data-requires-confirmation]').requireConfirmation();
  });
})(jQuery);
