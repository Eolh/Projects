/* material동작처리 */
$(document).ready(function () {

    $.material.init();

    $(window).resize(function () {
        var height = $('.navbar').css('height');
        $('main').css('marginTop', height);
    });
});
/* all window */

$(document).ready(function () {

    $("#TodoClose").hide();
    $("#FileClose").hide();
    $("#List").hide();

    $("#search").autocomplete({
        source: ["/Main", "/Calendar", "/File", "/Friend", "/TodoList", "/FileList", "/Regist", "/addgroup/グループ名前", "/TodoRegist/やるべきこと/登録したい日付"]
    });

    $("#search").keyup(function (e) {
        if (e.keyCode == 13) {
            command();
        }
        var keyword = $(this).val();


        if (keyword == '') {
            $("#displaySearch").hide();
        } else {
            $.ajax({
                type: "POST",
                url: "/Friend/searchUser",
                data: {'name': keyword},
                cache: false,
                success: function (data) {

                    var a = JSON.parse(data);

                    $("#displaySearch").show();
                    $("#displaySearch").text(a[0].ID + a[0].name);
                    $("#displaySearch").append("<i id='friendadd" + a[0].UID + "'class='fa fa-user-plus' onclick='friendadd(" + a[0].UID + ")'></i>");

                }
            });
        }
        return false;

    });
});

function friendadd(friendUid) {
    $.ajax({
        type: "POST",
        url: "/Friend/friendadd",
        data: {'friend2': friendUid},
        success: function (data) {
            var a = JSON.parse(data);

            window.alert("친구 요청 되었습니다.");

            $("#friendadd" + friendUid).replaceWith("<i id='frienddelete" + friendUid + "' class='fa fa-user-times' onclick='frienddelete(" + a[1] + ")'></i>")

        }
    });
}
function frienddelete(friendNum) {
    $.ajax({
        type: "POST",
        url: "/Friend/frienddelete",
        data: {'friend_num': friendNum},
        success: function (data) {
            var a = JSON.parse(data);

            window.alert("친구 요청 취소되었습니다.");

            $("#frienddelete" + a[0].friend2).replaceWith("<i id='friendadd" + a[0].friend2 + "' class='fa fa-user-plus' onclick='friendadd(" + a[0].friend2 + ")'></i>")

        }
    });
}

function command() {
    var search = $("#search").val();

    var command = search.toLowerCase();

    if (command.substr(0, 1) == '/') {

        if (command.substr(1, 5) == 'regis') {
            $("#List").hide();
            $("#uploadSchedule2").modal('show');
        }
        else if (command.substr(1, 5) == 'todor') {
            var data = command.split("/");

            $.ajax({
                type: 'POST',
                url: '/TodoList/inserttodo',
                data: {
                    'work': data[2],
                    'start_day': data[3]
                },
                success: function (html) {

                    window.alert("등록 되었습니다.");
                    $("#search").val("");
                }
            });
        }
        else if (command.substr(1, 5) == 'todol') {

            $.ajax({
                type: 'POST',
                url: '/TodoList/NoteTodo',
                data: {},
                success: function (html) {
                    $("#search").val("");
                    $("#List").show();
                    $("#ViewList").html(html);
                    $("#ViewList").next().remove();
                    $("#FileClose").hide();
                    $("#TodoClose").show();

                }
            });
        }
        else if (command.substr(1, 5) == 'filel') {

            $.ajax({
                type: 'POST',
                url: '/File/FileList',
                data: {},
                success: function (html) {
                    $("#search").val("");
                    $("#List").show();
                    $("#ViewList").html(html);
                    $("#ViewList").next().remove();
                    $("#TodoClose").hide();
                    $("#FileClose").show();
                }
            });
        }
        else if (command.substr(1, 8) == 'addgroup') {

            var addgroup = command.split("/");

            $.ajax({
                type: "POST",
                url: '/Friend/addgroup',
                data: {'gname': addgroup[2]},
                success: function (data) {

                    window.alert("등록 되었습니다.");

                    location.href = 'https://balancemyschedule.tk';

                }
            });
        }
        else {
            location.href = 'https://balancemyschedule.tk' + command;
        }
    }
}

function regist() {

    var form = $("#registerScheduler2")[0];
    var formData = new FormData(form);
    var groupCalRegister = $("#groupCalRegister option:selected").attr("id");

    var f_time = $("#datepicker1").val() + " " + $("#sch_f_time").val();
    var l_time = $("#datepicker2").val() + " " + $("#sch_l_time").val();
    var ucNum = groupCalRegister.substr(0, 1);

    formData.append("UCNum", ucNum);
    formData.append("start", f_time);
    formData.append("end", l_time);
    formData.append("groupCalRegisterOption", groupCalRegister);

    console.log(form);

    $.ajax({
        type: 'POST',
        url: '/ajaxHandler/insertCalendarSchedule',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            window.alert('등록성공');
        }
    });
}

function TodoClose() {
    $("#List").hide();
    $("#ViewList").next().remove();
    $("#TodoClose").hide();
}

function FileClose() {
    $("#List").hide();
    $("#ViewList").next().remove();
    $("#FileClose").hide();
}

/*datepicker1,2*/
$(function () {
    $("#datepicker1").datepicker({
        dateFormat: 'yy-mm-dd'
    });
});

$(function () {
    $("#datepicker2").datepicker({
        dateFormat: 'yy-mm-dd'
    });
});