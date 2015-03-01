$(document).ready(function(e) {
    try {
        $("body select").msDropDown();
        } catch(e) {
            alert(e.message);
    }
});

$(document).ready(function() {
    $("ul.menu li a").click(function() {
        $(this).parent().find("ul.sub-menu").slideToggle();
        $(this).toggleClass("actived");
    });

    $('#webmenu').on('change', function() {
        var link = $(this).val();
        location.href = link;
    });
});

function triggerBookmark(teaching_id){
    var self = this;
    var addBookmark = ($(this).hasClass('fav') == false) ? this : $('div.favbox a[data-id='+teaching_id+']');
    var pathBookmarkAction;
    if ($(self).hasClass('active')) {
        $(self).removeClass('active');
        $(addBookmark).addClass('unactive');
        $(addBookmark).text('AJOUTER A MES FAVORIS');
        $.getJSON(Routing.generate('espace_members_bookmark_remove', { id: teaching_id }))
        .fail(function(response) {
            $(self).addClass('active');
            if (addBookmark) {
                $(addBookmark).removeClass('unactive');
                $(addBookmark).text('ENLEVER DE MES FAVORIS');
            }
        });
    } else {
        $(self).addClass('active');
        $(addBookmark).removeClass('unactive');
        $(addBookmark).text('ENLEVER DE MES FAVORIS');
        $.getJSON(Routing.generate('espace_members_bookmark_add', { id: teaching_id }))
        .fail(function(response) {
            $(self).removeClass('active');
            if (addBookmark) {
                $(addBookmark).addClass('unactive');
                $(addBookmark).text('AJOUTER A MES FAVORIS');
            }
        });
    }
}

function arrowAction(teachings, teachingCurr) {
    var leftArrow  = $('div.num-list a.left-arrow');
    var rightArrow = $('div.num-list a.right-arrow');
    if($(teachings).find('li').first().attr('data-serial') == $(teachingCurr).attr('data-serial') &&
        $(teachings).find('li').last().attr('data-serial') == $(teachingCurr).attr('data-serial')) {
            $(rightArrow).attr('href', '#');
            $(rightArrow).removeClass('right-arrow-actived');
            $(leftArrow).attr('href', '#');
            $(leftArrow).removeClass('left-arrow-actived');
    } else if($(teachings).find('li').first().attr('data-serial') == $(teachingCurr).attr('data-serial')) {
        $(leftArrow).attr('href', '#');
        $(leftArrow).removeClass('left-arrow-actived');
        if($(teachings).find('li').last().attr('data-serial') == $(teachingCurr).attr('data-serial')) {
            $(rightArrow).attr('href', '#')
            $(rightArrow).removeClass('right-arrow-actived');
        } else {
            $(rightArrow).attr('href', $(teachingCurr).next().find('a.play').attr('href'));
            $(rightArrow).addClass('right-arrow-actived');
        }
    } else if($(teachings).find('li').last().attr('data-serial') == $(teachingCurr).attr('data-serial')) {
        $(rightArrow).attr('href', '#');
        $(rightArrow).removeClass('right-arrow-actived');
        $(leftArrow).attr('href', $(teachingCurr).prev().find('a.play').attr('href'));
        $(leftArrow).addClass('left-arrow-actived');
    } else {
        $(rightArrow).attr('href', $(teachingCurr).next().find('a.play').attr('href'));
        $(rightArrow).addClass('right-arrow-actived');
        $(leftArrow).attr('href', $(teachingCurr).prev().find('a.play').attr('href'));
        $(leftArrow).addClass('left-arrow-actived');
    }
}

$(document).ready(function() {
    $('.favbox a').on('click', function(){
        var teaching_id = $(this).attr('data-id');
        triggerBookmark.call(this, teaching_id);
        return false;
    });
    $('.sub-menu li a.fav').on('click', function(){
        var teaching_id = $(this).attr('data-id');
        triggerBookmark.call(this, teaching_id);
        return false;
    });

    var teachings = $('ul.sub-menu');
    var teachingCurrId = $('ul.menu a[data-curr-serial]').attr('data-curr-serial');
    var teachingCurr = $(teachings).find('[data-serial='+teachingCurrId+']');
    arrowAction.call(this, teachings, teachingCurr);

    $('div.num-list a.left-arrow').on('click', function(){
        var teachings = $('ul.sub-menu');
        var teachingCurrId = $('ul.menu a[data-curr-serial]').attr('data-curr-serial');
        var teachingCurr = $(teachings).find('[data-serial='+teachingCurrId+']');
        if($(teachings).find('li').first().attr('[data-serial]') !== $(teachingsCurr).attr('[data-serial]')) {
            teachingsCurr = $(teachingsCurr).prev();
        }

        arrowAction.call(this, teachings, teachingCurr);
        return false;
    });
});
