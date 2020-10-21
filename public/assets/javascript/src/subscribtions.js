$(document).ready(function() {
     function moveItem(button, currentList, targetList) {
        var itemContainer = button.closest('.item.well');
        // before moving last item
        if (itemContainer.parent().find('.item').length <= 1) {
            $(currentList + ' .blank-list-notice').show();
        }
        // move item
        button.toggleClass('subscribe-button unsubscribe-button');
        itemContainer
                .find('i')
                .toggleClass('fa-plus fa-minus');
        itemContainer.appendTo(targetList);
        // after moving to empty list
        $(targetList + ' .blank-list-notice').hide();
     }

    $(document).on('click', '.subscribe-button', function() {
        var button = $(this);
        var forumID = button.data('forum-id');
        $.ajax({
            type: "PUT",
            url: `/subscription/${forumID}`,
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            dataType: "json"
        }).done(function(response) {
            if (response.result > 0) {
                moveItem(button, '#forumsListUnSubscribed', '#forumsListSubscribed');
            } else {
                alert('cannot, pls retry');
            }
        }).fail(function() {
            alert('cannot, pls retry');
        });
    });

    $(document).on('click', '.unsubscribe-button', function() {
        var button = $(this);
        var forumID = button.data('forum-id');
        $.ajax({
            type: "DELETE",
            url: `/subscription/${forumID}`,
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            dataType: "json"
        }).done(function(response) {
            if (response.result > 0) {
                moveItem(button, '#forumsListSubscribed', '#forumsListUnSubscribed');
            } else {
                alert('cannot, pls retry');
            }
        }).fail(function() {
            alert('cannot, pls retry');
        });
    });
});