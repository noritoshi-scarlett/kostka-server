$(document).ready(function () {

    var textNotHere = "Ulala, ten wynik rzutu kością nie należy do tego postu O.O";
    var textCannotGetQrCode = 'Uzyskiwanie kodu ise nie powiodło. Spróbuj jeszcze raz.';
    var textNotConnection = "Wystąpiły błędy podczas łączenia T_T. \n\
        Kliknij we mnie aby sprawdzić rzut na stronie Postarium Kostka!";
    
    var linkForLoadingRoll = 'https://postarium.pl/kostka/dices/view/';
    
    var diceTypesArray = ['2', '4', '6', '8', '10', '12', '20', '100'];
    
    var buttonForGenerateQrCode = $('#btnGenerateRollingLink');
    
    var postariumAddress = 'http://kostka-rzut.pl.local:8080';
    //var postariumAddress = 'https://postarium.pl/';
    var postariumGenerateLink = postariumAddress + '/dice/generate';
    var forumAddress = location.hostname.split('.');
    var forumDomain = forumAddress.shift();
    var forumCodeName = 'eden-pbf';
    var forumToken = '28h902wc28wn8d0a';
    var rollingContainer = $('#rollingContainer');
    var qrContainer = $('#rollingContainer .qr-container');
    var iframeContainer = $('#rollingContainer .iframe-container');
    var username = $('.username.logout').text();
    var usernameData = username ? username : new Time();
    
    $(buttonForGenerateQrCode).on('click', function () {
        var topicTitle = 'topic title';
        var topicNumber = $('input[type="hidden"][name="t"]').val();
        var forumNumber = $('input[type="hidden"][name="f"]').val();

        $.ajax({
            type: "POST",
            url: postariumGenerateLink + '/' + forumCodeName + '/' + forumToken,
            crossDomain: true,
            dataType: "json",
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                forum_domain: encodeURIComponent(forumDomain),
                title: topicTitle,
                meta_data: {
                    username: usernameData,
                    topic: topicNumber,
                    forum: forumNumber
                }
            },
            success: function (response) {
                if (response.error) {
                    showSnackbar(textCannotGetQrCode, false);
                    return;
                }
                rollingContainer.show();
                showContainer(response.link);
                showQrCode(response.image);
            },
            error: function () {
                showSnackbar(textNotConnection, false);
            }
        });
    });


    function showQrCode(imageData) {
        qrContainer
                .find('img')
                .attr('src', imageData);
    }
    
    function showContainer(iframeLink) {
        iframeContainer
                .find('iframe')
                .attr('src', iframeLink);
    }

    // kazdy rzut wklejony w post -> pobierz wynik i zaprezentuj
    $('a[href^="' + linkForLoadingRoll + '"]').each(function () {
        var link = $(this);
        $.ajax({
            type: "GET",
            url: link.attr('href'),
            crossDomain: true,
            contentType: "application/json; charset=utf-8",
            data: {
                ajax_request: true,
                forum_name: encodeURIComponent(forumName)
            },
            dataType: 'json',
            success: function (json) {
                if (json.status > 0) {
                    checkRoll(link, json);
                    link.text('');
                    $(json.dices).each(function (index, dice) {
                        link.append($('<div>').append(
                                $('<div class="dice-title">').text(dice.dice_name),
                                $('<div class="dice-desc">').text(dice.dice_desc),
                                $('<div class="dice-place' +
                                        (($.inArray(dice.dice_type, diceTypesArray) === -1)
                                                ? ' dice-custom"'
                                                : '" data-number="' + dice.dice_type + '"')
                                        + ' data-count="' + dice.dice_count + '">'
                                        ),
                                $('<div class="dice-type">').text("(1-" + dice.dice_type + ")"),
                                $('<div class="dice-values">').text(dice.dice_values)
                                ));
                    });
                } else {
                    someError(link, textNotHere);
                }
            },
            error: function () {
                someError(link, textNotConnection);
            }
        });
    });

    // blad w laczeniu -> kliknij i zobacz rzut bezpsrednio
    function someError(link, message) {
        link.wrap($('<div class="dice-cont">'));
        link.html($('<div class="dice-title">').text(message));
        link.children().append($('<div class="dice-place dice-error">'));
    }


    var snackbar_timer;

    // pokaz snackbar
    function showSnackbar(text, autohide) {
        $("#dice-snackbar").html(text);
        if ($("#dice-snackbar").hasClass("snackbar-show")) {
            clearTimeout(snackbar_timer);
        } else {
            $("#dice-snackbar").addClass("snackbar-show");
        }
        if (autohide) {
            snackbar_timer = setTimeout(function () {
                $("#dice-snackbar").removeClass("snackbar-show");
            }, 3500);
        }
    }

    // zamkniecie snackbara
    $(document).on("click", "#dice-snackbar", function () {
        clearTimeout(snackbar_timer);
        $(this).removeClass("snackbar-show");
    });
});