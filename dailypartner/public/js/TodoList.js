function navi() {
    var config = {
        $bookBlock : $('#bb-bookblock')
    };

    var date = $(".contents-title").text();

    var navId = date.split('.');

    for (var j = 0 ; j < navId.length ; j++) {

        $('#calendarClick' + navId[j]).click(function () {

            var day = $(this).attr('id').substr(13, 24);

            var page = $('#side' + day).parent().attr('id');

            config.$bookBlock.bookblock('jump', page);

            return false;
        });
    }
}
function inserttodo(startday) {

    var uid = "<?= $_SESSION['login']['UID'] ?>";

    var text = $("#create-task").val();

    $.ajax({
        type    : 'POST',
        url     : '/TodoList/inserttodo',
        data    : {
            'UID'       : uid,
            'work'      : text,
            'start_day' : startday
        },
        success : function (data) {
            $("#task-list").prepend(data);
        }
    });
}
function deletetodo(workNumber) {
    $.ajax({
        type    : 'POST',
        url     : '/TodoList/deletetodo',
        data    : {'work_num' : workNumber},
        success : function (data) {
            $("li#task-content" + workNumber).remove();
        }
    });
}
function edittodo(workNumber) {

    $("#edit" + workNumber).replaceWith("<button id='edit-btn" + workNumber + "'class='fa fa-check' onclick='modifytodo(" + workNumber + ")'></button>");
    $("#edit-content" + workNumber).css("visibility", 'visible');
}
/*("#edit-btn" + workNumber).replaceWith("<button id='edit<?=$todolist[0]->work_num?>' class='fa fa-pencil' onclick='edittodo(<?=$todolist[0]->work_num?>)'></button>");*/
function modifytodo(workNumber) {

    var text = $("#edit-content" + workNumber).val();

    $.ajax({
        type    : 'POST',
        url     : '/TodoList/modifytodo',
        data    : {
            'work'     : text,
            'work_num' : workNumber
        },
        success : function (data) {
            var a = JSON.parse(data);
            $("#check" + workNumber + " > a").text(a.work);
            $("#edit-content" + workNumber).css("visibility", 'hidden');
            $("#edit-btn" + workNumber).replaceWith("<button id='edit" + workNumber + "' class='fa fa-pencil' onclick='edittodo(" + workNumber + ")'></button>");
        }
    });
}

function checktodo(workNumber) {

    $.ajax({
        type    : 'POST',
        url     : '/TodoList/checktodo',
        data    : {
            'work_num' : workNumber
        },
        success : function (data) {
        }
    });
}

$('input[type=checkbox]').click(function () {

    if ($(this).next().attr("class") == 'completed_item')
        $(this).next().removeAttr("class");
    else
        $(this).next().attr("class", 'completed_item');

});

$(document).on('keypress', 'ul input[class="test"]', function (e) {

    if (e.which == 13) {
        $(this).next().click();
    }
});

$('#create-task').keypress(function (e) {
    if (e.which == 13) {
        $("#insert-btn").click();
    }
});

$(function () {
    $("#TodoBtn").click(function () {
        $("#slideE").toggle('slide', {direction : "right"}, 'easeOutExpo', 500);
    });
});

var Page = (function () {

    var config = {
            $bookBlock : $('#bb-bookblock'),
            $navNext   : $('#bb-nav-next'),
            $navPrev   : $('#bb-nav-prev'),
            $navFirst  : $('#bb-nav-first'),
            $navLast   : $('#bb-nav-last'),
            $navJump   : $('#page3')
        },
        init = function () {
            config.$bookBlock.bookblock({
                speed       : 1000,
                shadowSides : 0.8,
                shadowFlip  : 0.4
            });
            initEvents();
        },
        initEvents = function () {

            var $slides = config.$bookBlock.children();

            // add navigation events
            config.$navNext.on('click touchstart', function () {
                config.$bookBlock.bookblock('next');
                return false;
            });

            config.$navPrev.on('click touchstart', function () {
                config.$bookBlock.bookblock('prev');
                return false;
            });

            config.$navFirst.on('click touchstart', function () {
                config.$bookBlock.bookblock('first');
                return false;
            });

            config.$navLast.on('click touchstart', function () {
                config.$bookBlock.bookblock('last');
                return false;
            });

            $('#page3').click(function () {
                config.$bookBlock.bookblock('jump', 5);
                return false;
            });

            // add swipe events
            $slides.on({
                'swipeleft'  : function (event) {
                    config.$bookBlock.bookblock('next');
                    return false;
                },
                'swiperight' : function (event) {
                    config.$bookBlock.bookblock('prev');
                    return false;
                }
            });

            // add keyboard events
            $(document).keydown(function (e) {
                var keyCode = e.keyCode || e.which,
                    arrow = {
                        left  : 37,
                        up    : 38,
                        right : 39,
                        down  : 40
                    };

                switch (keyCode) {
                    case arrow.left:
                        config.$bookBlock.bookblock('prev');
                        break;
                    case arrow.right:
                        config.$bookBlock.bookblock('next');
                        break;
                }
            });
        };

    return {init : init};

})();
Page.init();
