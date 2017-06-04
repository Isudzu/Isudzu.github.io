/**
 * Ajax subscribe and display result.
 */
(function ($) {
    $( function () {
        'use strict';
        var $form = $('.js-sp-form'),
            init = function () {
                $form
                    .on('submit', onSubmitForm);
            },
            onSubmitForm = function ( e ) {

            e.preventDefault();

            $form.addClass('loading');

             $.post( sp_params.ajax_url, $form.serialize(), function(response) {
                 var data = response.data,
                     errorClass = (response.success === false) ? 'error' : '';
                 $form
                     .removeClass('loading')
                     .find('.sp-form__message')
                     .text( data.msg )
                     .addClass('show ' + errorClass);
             });

            };

        init();
    });
})(jQuery);
