(($) ->
  $.fn.extend
    requireConfirmation: ->
      return @each () ->
        $(this).on 'click', (event) ->
          event.preventDefault();

          actionButton = $(this);

          if actionButton.is 'a'
            $('#confirmation-button').attr 'href', actionButton.attr 'href'

          if actionButton.is 'button'
             $('#confirmation-button').on 'click', (event) ->
                event.preventDefault();
                actionButton.closest('form').submit()

          $('#confirmation-modal').modal 'show'

  $(document).ready ->
    $('#sidebar')
      .first()
      .sidebar 'attach events', '#sidebar-toggle', 'show'

    $('.ui.checkbox')
      .checkbox()

    $('.ui.radio.checkbox')
      .checkbox()

    $('.menu .item')
      .tab()

    $('.helper')
      .popup()

    $('select')
      .dropdown()

    $('.form button').on 'click', ->
      $(this)
        .addClass 'loading'

    $('.message .close').on 'click',  ->
      $(this)
      .closest '.message'
      .transition 'fade'

    $('.primary').css('background-color', '#1abb9c');

    $('[data-requires-confirmation').requireConfirmation()

    $('#sylius_shipping_method_calculator').handlePrototypes({
      'prototypePrefix': 'sylius_shipping_method_calculator_calculators',
      'containerSelector': '.configuration'
    });
) jQuery
