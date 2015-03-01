function triggerBookmark(teaching_id) {
    var self = this;
    if ($(self).hasClass('active')) {
        $('div#content').find('[data-id='+teaching_id+']').removeClass('active');
        $.getJSON(Routing.generate('espace_members_bookmark_remove', { id: teaching_id }), function(response) {
            $(self).hasClass('bookmark-page') ?
                location.reload() :
                $('div#content').find('[data-id='+teaching_id+']').removeClass('active');
        })
        .fail(function(xhr, textStatus, errorThrown) {
            $('div#content').find('[data-id='+teaching_id+']').addClass('active');
        });
    } else {
        $('div#content').find('[data-id='+teaching_id+']').addClass('active');
        $.getJSON(Routing.generate('espace_members_bookmark_add', { id: teaching_id }))
        .fail(function(xhr, textStatus, errorThrown) {
            $('div#content').find('[data-id='+teaching_id+']').removeClass('active');
        });
    }
}

$(document).ready(function() {
    $('.item a.follow').on('click', function() {
        triggerBookmark.call(this, $(this).attr('data-id'));

        return false;
    });

    $('#accordion h3').on('click', function() {
        if ($(this).hasClass('ui-active-accordion')) {
            $(this).removeClass('ui-active-accordion');
            $('#accordion').accordion({ collapsible: true, active: false });
        } else {
            $('#accordion h3').removeClass('ui-active-accordion');
            $(this).addClass('ui-active-accordion');
        }

        return false;
    });
});
