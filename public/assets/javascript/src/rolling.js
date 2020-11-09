$(document).ready(function() {
    // fill values
    function updateExample() {
        var textVal = '';
        var dicesCount = parseInt($("#formInputDicesCont").val());
        // coin data
        var exampleValues = $('.dice-place.active').attr('data-number') === '2'
            ? ['orzeł', 'reszka']
            : ['1', '2'];

        if (!$.isNumeric(dicesCount)) {
            textVal = '1';
        } else {
            for (var i = 1; (i < dicesCount && i < 10); i++) {
                textVal += exampleValues[0] + ', ';
            }
            textVal += exampleValues[1];
        }
        $('.example-view .dice-values').text(textVal);
    }

    // bind updating
    $("#inputDiceRollName").on('input', function() {
        $('.example-view .dice-title').text($(this).val());
    });
    $("#inputDiceRollDesc").on('input', function() {
        $('.example-view .dice-desc').text($(this).val());
    });
    $("#formInputDicesCont").on('input', function() {
        updateExample();
    });
    $("#formInputDicesCustom").on('input', function() {
        $('.example-view .dice-type span').text($(this).val());
    });

    // change selected dice
    $('.dice-place').click(function () {
        var diceSelector = $(this);
        var selectedType = diceSelector.attr('data-number');
        var isCustomDice = selectedType === '1'; 
        var selectedTypeInformation = $('#inputDiceType');
        // change acvitity
        $('.dice-place').removeClass('active');
        diceSelector.addClass('active');
        // unlock form
        $('#formDicesRoll button[type="submit"]').removeAttr("disabled");

        // set values in form
        $('#formInputDicesType').val(selectedType);
        selectedTypeInformation.text(selectedTypeInformation.data('selected') + 'k' + selectedType);
        // set values in example
        $('.example-view .dice-type span').text(selectedType);
        $('.example-view .dice-place').attr('data-number', selectedType);
        /// set as custom
        if (isCustomDice) {
            $('#formInputDicesCustom').removeClass('hidden').val('');
            selectedTypeInformation.hide();
        } else {
            $('#formInputDicesCustom').addClass('hidden').val(6);
            selectedTypeInformation.show();
        }
        updateExample();
    });
});