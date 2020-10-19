$(document).ready(function() {

    $('.tabgroup > div').hide();
    $('.tabgroup > div:first-of-type').show();
    $('.tabs a').click(function(e){
        e.preventDefault();
        var $this = $(this),
            tabgroup = '#'+$this.parents('.tabs').data('tabgroup'),
            others = $this.closest('li').siblings().children('a'),
            target = $this.attr('href');
        others.removeClass('active');
        $this.addClass('active');
        $(tabgroup).children('div').hide();
        $(target).show();
    });

});

var site_base_url = window.location.protocol + "//" + window.location.host;
    
var load_forum_list = function(url_string, mode_list, icon_style, target_table) {
    $.ajax({
        type: "GET",
        url: url_string,
        async: false,
        dataType: "json",
        success: function(data) {
            $.each(data, function(i, item) {
				if (icon_style == "fa-minus") {
					$('<tr>').append(
						$('<td>').append($('<div>').append('<a href="' + site_base_url + '/kostka/library/index/' + mode_list + '/' + item.forum_id + '.html"><i class="fa fa-2x ' + icon_style + '"></i></a>')),
						$('<td>').text(item.forum_title).append(
							$('<br>'),
							$('<a href="' + item.forum_url + '"><').text(item.forum_url))
					).appendTo(target_table);
				} else {
					$('<tr>').append(
						$('<td>').text(item.forum_title).append(
							$('<br>'),
							$('<a href="' + item.forum_url + '"><').text(item.forum_url)),
						$('<td>').append($('<div>').append('<a href="' + site_base_url + '/kostka/library/index/' + mode_list + '/' + item.forum_id + '.html"><i class="fa fa-2x ' + icon_style + '"></i></a>')),
					).appendTo(target_table);
				}
            });
        }
    });
};

var load_forum_list_unlog = function(url_string, target_table) {
    $.ajax({
        type: "GET",
        url: url_string,
        async: false,
        dataType: "json",
        success: function(data) {
            $.each(data, function(i, item) {
                $('<div class="one-half column well well-hover">')
					.text(item.forum_title)
					.append(
						$('<br>'),
						$('<a href="' + item.forum_url + '"><').text(item.forum_url))
					.appendTo(target_table);
            });
        }
    });
};