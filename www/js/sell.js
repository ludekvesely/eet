$(function() {
    var insertType = 'quick';

    $('#more-amount').click(function() {
        $('#amount').show();
        $('#addSlow').show();
        $('#more-amount').hide();

        insertType = 'slow';

    });

    $('.form .submit').click(function(e) {
        if(insertType == 'slow') {
            e.preventDefault();

            $('#amount').val($('#amount').val()+$(this).val());
        } else {
            $('#amount').val($(this).val());
        }

    });


});