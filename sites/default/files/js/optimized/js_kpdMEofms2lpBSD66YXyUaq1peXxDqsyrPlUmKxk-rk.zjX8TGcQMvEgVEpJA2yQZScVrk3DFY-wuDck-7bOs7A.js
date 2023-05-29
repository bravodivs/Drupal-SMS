(function ($, Drupal) {
  Drupal.behaviors.mymoduleCustomJs = {
    attach: function (context, settings) {
      $('.attendance-status-button').click(function () {
        var button = $(this);
        var studentId = button.data('student-id');
        var currentStatus = button.data('status');
        var newStatus = (currentStatus === 'present') ? 'absent' : 'present';

        // Update the button text.
        button.val(newStatus);

        // Update the button data attribute.
        button.data('status', newStatus);

        // Update the hidden input value.
        $('#status-' + studentId).val(newStatus);
      });
    }
  };
})(jQuery, Drupal);
