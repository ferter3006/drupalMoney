/**
 * JavaScript para el formulario de creación de tickets
 */

(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.moneylinkCreateTiquetForm = {
    attach: function (context, settings) {
      // Agregar efectos de hover y validación en tiempo real
      
      // Validación en tiempo real para el campo de cantidad
      $('.form-number', context).once('amount-validation').on('input', function() {
        var $this = $(this);
        var value = parseFloat($this.val());
        
        if (value <= 0 && $this.val() !== '') {
          $this.addClass('error');
        } else {
          $this.removeClass('error');
        }
      });

      // Contador de caracteres para la descripción
      $('.form-textarea', context).once('char-counter').on('input', function() {
        var $this = $(this);
        var length = $this.val().length;
        var minLength = 10;
        
        // Crear o actualizar contador si no existe
        var $counter = $this.siblings('.char-counter');
        if ($counter.length === 0) {
          $counter = $('<div class="char-counter"></div>');
          $this.after($counter);
        }
        
        if (length < minLength) {
          $counter.text('Mínimo ' + minLength + ' caracteres (faltan ' + (minLength - length) + ')');
          $counter.css('color', '#dc3545');
        } else {
          $counter.text(length + ' caracteres');
          $counter.css('color', '#28a745');
        }
      });

      // Animación suave para los elementos del formulario
      $('.form-section', context).once('animation').each(function(index) {
        $(this).css({
          'animation-delay': (index * 0.1) + 's'
        });
      });

      // Mejorar la experiencia de los radio buttons
      $('.movement-type-radios input[type="radio"]', context).once('radio-enhancement').on('change', function() {
        var $wrapper = $(this).closest('.movement-type-radios');
        var $labels = $wrapper.find('label');
        
        $labels.removeClass('selected');
        $(this).siblings('label').addClass('selected');
      });
    }
  };

})(jQuery, Drupal);