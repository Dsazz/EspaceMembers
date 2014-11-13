function triggerBookmark(teaching_id)
{
    var self = this;
    if ($(self).hasClass('active')) {
        $('div#content').find('[data-id='+teaching_id+']').removeClass('active');
        $.get(Routing.generate('espace_members_bookmark_remove', { id: teaching_id }), {},
            function(response){
                if(response.success == true) {
                    $(self).hasClass('bookmark-page')
                        ? location.reload()
                        : $('div#content').find('[data-id='+teaching_id+']').removeClass('active');
                } else {
                    $('div#content').find('[data-id='+teaching_id+']').addClass('active');
                }
            }, "json");
    } else {
        $('div#content').find('[data-id='+teaching_id+']').addClass('active');
        $.get(Routing.generate('espace_members_bookmark_add', { id: teaching_id }), {},
            function(response){
                if(response.success == false){
                    $('div#content').find('[data-id='+teaching_id+']').removeClass('active');
                }
            }, "json");
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
