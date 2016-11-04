<!DOCTYPE html>
<html>
<head>
    <!-- start: PAGE SETTINGS -->
    <title>balancemyschedule</title>
    <!-- start: META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- end:   META -->
    <!-- end:   PAGE SETTINGS -->

    <!--================================== start: Font =================================-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,600' rel='stylesheet' type='text/css'>

    <!-- Bootstrap Material fonts -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- end:   font -->

    <!--================================== start: CSS =================================-->
    <link rel="stylesheet" href="/public/export_modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/public/css/animate.css">

    <!-- Bootstrap Material Design -->
    <link rel="stylesheet" type="text/css" href="/public/export_modules/dist/css/bootstrap-material-design.css">
    <link rel="stylesheet" type="text/css" href="/public/export_modules/dist/css/ripples.min.css">

    <!-- Todolist & Command -->
    <link rel="stylesheet" href="/public/export_modules/jquery-ui/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="/public/css/bookblock.css"/>
    <link rel="stylesheet" type="text/css" href="/public/css/todoList.css"/>

    <!-- This page -->
    <link rel="stylesheet" href="/public/css/main_header.css">
    <link rel="stylesheet" href="/public/export_modules/fullcalendar/fullcalendar.css">
    <link rel="stylesheet" href="/public/css/fullcalendar.css">
    <link rel="stylesheet" href="/public/css/fullcalendar.print.css">
    <link rel="stylesheet" href="/public/css/sideTodo.css">

    <style>
        img {
            width: 25px;
            height: 25px;
            padding: 1px;
            margin: 1px;
        }

        #maps {
            height: 400px;
        }

        .modal:nth-of-type(even) {
            z-index: 1043 !important;
        }

        .modal-backdrop.in:nth-of-type(even) {
            z-index: 1040 !important;
        }

        .pac-container {
            z-index: 1051 !important;
        }

        #detail_schedule {
            width: 80%;
        }

        #registerScheduler .form-group {
            margin-top: 0;
        }

        #registerScheduler .form-group .vertical-top {
            margin-top: 0;
        }

        .block-menu {
            vertical-align: top;
            border-left: 3px solid rgba(224, 232, 232, 0.7);
        }

        .block-menu:hover {
            border-left: 3px solid rgba(87, 124, 189, 0.7);
        }
    </style>
    <!-- end:   CSS -->

    <!--================================== start: script =================================-->
    <script src="/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="/public/export_modules/jquery-ui/jquery-ui.min.js"></script>
    <script src="/public/export_modules/bootstrap/js/bootstrap.min.js"></script>

    <!-- Todolist & Command -->
    <script src="/public/js/modernizr.custom.js"></script>
    <script src="/public/js/jquerypp.custom.js"></script>
    <script src="/public/js/jquery.bookblock.js"></script>

    <!-- Bootstrap Material Js -->
    <script src="/public/export_modules/dist/js/material.min.js"></script>
    <script src="/public/export_modules/dist/js/ripples.min.js"></script>

    <!-- This page -->
    <script src="/public/export_modules/fullcalendar/lib/jquery-ui.custom.min.js"></script>
    <script src="/public/export_modules/fullcalendar/lib/moment.min.js"></script>
    <script src="/public/export_modules/fullcalendar/fullcalendar.js"></script>
    <script src="/public/export_modules/fullcalendar/lang/ja.js"></script>

    <script type="text/javascript"
            src="https://maps.google.com/maps/api/js?key=AIzaSyCYCSJRgNsqrQbsTQhw1FUb6FhhhZXI3y4&v=3.23&language=ja&libraries=places"
            async defer></script>
    <script src="/public/js/weather.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>


    <!--material동작처리-->
    <script>
        $(document).ready(function () {
            $.material.init();
        });

        $(window).resize(function () {
            var height = $('.navbar').css('height');
            $('main').css('marginTop', height);
        });
    </script>

    <!--Command Js-->
    <script src="/public/js/command.js"></script>

    <!--calendar-->
    <script>

        var map;
        var temper = <?=json_encode($groupInfo);?>;
        var tempOption = "";
        var tempSelect, tempStoreSelect;
        var temperBefore, temperAfter;

        temperBefore = "<select name='groupCalendar' id='groupCal' onchange='foo()'>";
        temperAfter  = "</select>";

        for (var i in temper) {
            tempOption +=
                "<option id='" + temper[i].UCNum + "'>"
                + temper[i].calName +
                "</optrion>";
        }

        function foo() {

            var temperOption = $("#groupCal option:selected").attr("id");
            tempSelect = $("#groupCal")[0];
            $("#registerScheduler #groupCalRegister #" + temperOption + "Register").attr("selected", "selected");
            //$("#calendar").fullCalendar("addEventSource", "/calendar/getCalendarList/" + temperOption);


            var moment = $("#calendar").fullCalendar('getDate');



            $("#calendar").remove();
            $(".container").append("<div id='calendar'></div>");
            getCalendar(temperOption);
            $("#calendar").fullCalendar("gotoDate", moment);
            $("#calendar .fc-left").append(tempSelect);
        }

        tempSelect = temperBefore + tempOption + temperAfter;

        $(document).ready(function () {

            /* 이 부분을 바꿀 방법이 없나??? */

            getCalendar("");
            $("#calendar .fc-left").append(tempSelect);
        });

        function getCalendar(getOption) {
            $("#calendar").fullCalendar({
                firstDay: 0,
                allDaySlot  : false,
                header: {
                    left: 'prev,next today myCustomButton',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                customButtons: {
                    myCustomButton: {
                        text: 'カレンダー登録',//그룹등록
                        click: function () {

                            $('#uploadGroup').modal('show');

                            $("a[id='groupAddBtn']").unbind('click');
                            $("a[id='groupAddBtn']").click(function () {

                                var check = true;
                                var groupName = $("#grpName").val();

                                if (!groupName) return false;

                                $("#groupCal option").each(function () {
                                    if (groupName == $(this).text()) {
                                        window.alert("もうあります");//이미 있습니다.
                                        check = false;
                                    }
                                });

                                if (!check) { check = true; return false; }

                                var form = $("#registerGroup")[0];
                                var formData = new FormData(form);

                                $.ajax({
                                    type: "POST",
                                    url: '/ajaxHandler/insertGroup',
                                    data: formData,
                                    async: false,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success: function (json) {

                                        good = JSON.parse(json);

                                        $("#groupCal").append("<option id='" + good.UCNum + "'>" + good.calName + "</option>");
                                        $("#registerScheduler #groupCalRegister").append("<option id='" + good.UCNum + "Register'>" + good.calName + "</option>");

                                        tempSelect = $("#groupCal")[0];

                                        console.log(tempSelect);
                                    },
                                    error: function (json) {
                                        $("#test").append(json);
                                    }
                                });
                            }); /* end of click event */
                        }
                    }
                },
                editable: true,
                selectable: true,
                selectHelper: true,
                select: function (start, end, jsEvent, view) {

                    $('#uploadSchedule').modal('show');

                    start = moment(start).format('YYYY-MM-DD HH:mm:ss');
                    end = moment(end).format('YYYY-MM-DD HH:mm:ss');

                    $.ajax({

                        type: 'POST',
                        url: '/TodoList/selecttodoindate',
                        data: {'start_day': start.split(" ")[0]},
                        success: function (data) {

                            $(".slideDiv").replaceWith(data);
                        }
                    });

                    if (view.name == "month")
                        end = start;

                    $("#registerScheduler #sch_f_time").attr("value", start.split(" ")[0]);
                    $("#registerScheduler #sch_l_time").attr("value", end.split(" ")[0]);

                    $("#registerScheduler #sch_f_time_min").attr("value", start.split(" ")[1]);
                    $("#registerScheduler #sch_l_time_min").attr("value", end.split(" ")[1]);

                    $("a[id='scheduleAddBtn']").unbind('click');
                    $("a[id='scheduleAddBtn']").click(function () {

                        var title = $("#title").val();
                        var content = $("#content").val();
                        var startMin = $("#sch_f_time_min").val();
                        var endMin = $("#sch_l_time_min").val();
                        var SdNum;

                        if (!title) {
                            window.alert("タイトルを入れてください!!!");
                            return false;
                        } else if (!content) {
                            window.alert("内容を入れてください!!!");
                            return false;
                        } else if (!startMin) {
                            window.alert("始まる時間を入れてください!!!");
                            return false;
                        } else if (!endMin) {
                            window.alert("終わる時間を入れてください!!!");
                            return false;
                        }

                        if (title && content) {

                            var form = $("#registerScheduler")[0];
                            var formData = new FormData(form);
                            var groupCalRegister = $("#groupCalRegister option:selected").attr("id");

                            start = $("#registerScheduler #sch_f_time").val() + " " + startMin;
                            end = $("#registerScheduler #sch_l_time").val() + " " + endMin;

                            var calendarNum = $("#groupCal option:selected").attr("id");

                            formData.append("UCNum", calendarNum);
                            formData.append("start", start);
                            formData.append("end", end);
                            formData.append("groupCalRegisterOption", groupCalRegister);

                            $.ajax({
                                type: "POST",
                                url: '/ajaxHandler/insertCalendarSchedule',
                                data: formData,
                                async: false,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function (json) {
                                    SdNum = json;

                                    var className = formData.get('color');

                                    switch (className) {
                                        case "0" : className = 'chill';     break;
                                        case "1" : className = 'info';      break;
                                        case "2" : className = 'important'; break;
                                        case "3" : className = 'success';   break;
                                    }

                                    $("#calendar").fullCalendar('renderEvent', {
                                            SdNum     : SdNum,
                                            title     : title,
                                            start     : start,
                                            end       : end,
                                            UCNum     : calendarNum,
                                            className : className
                                        }, true // make the event "stick"
                                    );
                                },
                                error: function (json) {

                                    $("#calendar").fullCalendar('renderEvent',
                                        {
                                            SdNum     : SdNum,
                                            title     : title,
                                            start     : start,
                                            end       : end,
                                            UCNum     : calendarNum,
                                            className : className
                                        },
                                        true // make the event "stick"
                                    );
                                }
                            });
                        }
                    });

                    $("#calendar").fullCalendar('unselect');

                    $("#registerScheduler").closest('form').find("input[id='title'], input[id='content'], input[id='tagName'], input[id='pac-input'], input[id='sch_file_pc'], input[id='sch_file'], textarea").val("");

                },
                events: "/calendar/getCalendarList/" + getOption,
                eventLimit: true,
                eventMouseover: function (event, jsEvent, view) { $(this).css("background-color", "green");   },
                eventMouseout: function (event, jsEvent, view)  { $(this).css("background-color", "#3a87ad"); },
                eventDrop: function (event, delta, revertFunc, jsEvent, ui, view) {

                    if (!confirm("変えるか")) {//바꿀?
                        revertFunc();
                        return false;
                    }

                    var start = moment(event.start).format('YYYY-MM-DD HH:mm:ss');
                    var end = moment(event.end).format('YYYY-MM-DD HH:mm:ss');

                    if (end == "Invalid date" && event.allDay == true) {
                        start = moment(event.start).format('YYYY-MM-DD');
                        var parts = start.split("-");
                        end = parts[0] + "-" + parts[1] + "-" + (parseInt(parts[2]));
                    } else if (end == "Invalid date" && event.allDay == false) {
                        start = moment(event.start).format('YYYY-MM-DD HH:mm:ss');
                        parts = start.split(" ");
                        var parts2 = parts[1].split(":");
                        end = parts[0] + " " + (parseInt(parts2[0]) + 2) + ":" + parts2[1] + ":" + parts2[2];
                    } else if (start.split(" ")[1] == "00:00:00" && end.split(" ")[1] == "00:00:00") {
                        start = moment(event.start).format('YYYY-MM-DD');
                        parts = start.split("-");
                        end = parts[0] + "-" + parts[1] + "-" + (parseInt(parts[2]));
                    }

                    $.ajax({
                        url: '/ajaxHandler/updateCalendarScheduleToResize',
                        data: {
                            title: event.title,
                            start: start,
                            end: end,
                            id: event.id,
                            SdNum: event.SdNum
                        },
                        type: "POST"
                    });
                },
                eventResize: function (event, delta, revertFunc, jsEvent, ui, view) {
                    if (!confirm("???")) {
                        revertFunc();
                        return false;
                    } else {
                        var start = moment(event.start).format('YYYY-MM-DD HH:mm:ss');
                        var end = moment(event.end).format('YYYY-MM-DD HH:mm:ss');

                        $.ajax({
                            url: '/ajaxHandler/updateCalendarScheduleToResize',
                            data: {
                                title: event.title,
                                start: start,
                                end: end,
                                id: event.id,
                                SdNum: event.SdNum
                            },
                            type: "POST"
                        });
                        /* 이걸로 계산합니다! */
                    }
                },
                eventClick: function (date, jsEvent, view) {
                    $('#myCarousel').carousel(0);
                    var start = moment(date.start).format('YYYY-MM-DD HH:mm:ss');
                    var end   = moment(date.end)  .format('YYYY-MM-DD HH:mm:ss');

                    var file = $('<ul></ul>').addClass('list-group').html('<li class="list-group-item list-group-item-info">File List</li>');

                    $.ajax({

                        url: '/ajaxHandler/getscheduleFile',
                        data: {
                            SdNum: date.SdNum
                        },
                        type: "POST",
                        success: function (data) {
                            data = JSON.parse(data);
                            var cnt = data.length;
                            if (cnt == 0)
                                $('<div></div>').appendTo(file).text("ファイルがありません");//파일이 없습니다.
                            else {
                                for (var i = 0; i < cnt; i++) {
                                    var FNum = data[i].FNum;

                                    var Flink = data[i].Flink;
                                    var FName = data[i].FName;
                                    var hashtag = data[i].hashtag;
                                    var link = $('<a></a>').attr('href', '/public/file/<?=$_SESSION['login']['ID']?>/' + FName);

                                    link.attr('download', Flink).text(Flink);
                                    $('<li></li>').addClass('list-group-item col-sm-1').text(i + 1).appendTo(file);
                                    $('<li></li>').addClass('list-group-item col-sm-7').html(link).appendTo(file);
                                    link.after('<button id="freeview" value="' + FName + '">a</button>');
                                    $('<li></li>').addClass('list-group-item col-sm-3').text((hashtag != null) ? hashtag.replace(/;/g, " ") : "없음").appendTo(file);

                                }
                            }
                        }
                    });

                    $.ajax({
                        url: '/ajaxHandler/getDetailSchedule',
                        data: {
                            SdNum: date.SdNum,
                            start: start,
                            end: end,
                            UCNum: date.UCNum
                        },
                        type: "POST",
                        success: function (data) {
                            data = JSON.parse(data);
//                            console.log(data);
                            /*위도와 경도 데이터를 가져옵니다.*/
                            if (data.latitude!=0.000000) {
                                LatLng = new google.maps.LatLng(Number(data.latitude),Number(data.longitude));

                                var myoptions = {
                                    disableDefaultUI: true,
                                    useCurrentLocations: true,
                                    center: LatLng,
                                    zoom: 13,
                                    mapTypeId: google.maps.MapTypeId.ROADMAP
                                };

                                var marker = new google.maps.Marker({
                                    position: LatLng
                                });


                                var map = new google.maps.Map(document.getElementById('mymap'), myoptions);
                                marker.setMap(map);

                                accuweather(data.latitude, data.longitude, 2);
                                setTimeout(function () {
                                    var center = map.getCenter();
                                    google.maps.event.trigger(map, 'resize');
                                    map.setCenter(center);
                                }, 200);

                                $("#location").addClass('show');


                            } else {
                                $("#location").removeClass('hidden');
                                $("#location").addClass('hidden');
                                $('#locationinfo').text("Not Setting place");
                            }
                            $('#schedulecontent').text(data.content);
                        }
                    });

                    /*$('.modal-header').html(date.title);
                     $('#schedulecontent').html(schedule);*/

                    console.log(date);
                    $('#scheduletitle').text(date.title);
                    $('#schedule_start').text(start);

                    $('#schedule_end').text(end);
                    $('#schedulefile').html(file);
                    $('#detail_schedule').modal("show");
                }
            });
        }

        $(document).on('click', '#freeview', function () {
            var Fname = $(this).val();
            $('#freediv').empty();
            $('<iframe></iframe>').css({
                'width': '100%',
                'height': '100%'
            }).attr('src', "https://docs.google.com/gview?url=https://133.130.125.87/public/file/<?=$_SESSION['login']['ID']?>/" + Fname + "&embedded=true").appendTo('#freediv');
            $('#freediv').show();
        });

    </script>
    <!-- end:   script -->
</head>
<body>
<div id="test"></div>
<?php require_once "application/views/common/header_main.php"; ?>

<main>
    <div id="List">
        <i class="fa fa-times" id="TodoClose" onclick="TodoClose()"></i>
        <i class="fa fa-times" id="FileClose" onclick="FileClose()"></i>

        <div id="ViewList">

        </div>
    </div>
    <?php require_once "application/views/main/common/rightSide_todo.php"; ?>
    <div class="container" style="padding-top: 20px;">
        <!-- Calendars -->
        <div id="calendar"></div>

        <!-- Calendar Scheduler -->
        <div class="modal" id="uploadSchedule" style="z-index: 1041">
            <?php require_once "application/views/calendar/emit/day_emit.php"; ?>
        </div>

        <div class="modal fade" id="setPlace" data-backdrop="static" style="z-index: 1043">
            <?php require_once "application/views/calendar/emit/place_emit.php"; ?>
        </div>

        <div class="modal fade" id="uploadGroup">
            <?php require_once "application/views/calendar/group/group_add.php"; ?>
        </div>


        <div class="modal fade" id="detail_schedule">
            <?php require_once "application/views/calendar/emit/detail_schedule.php"; ?>
        </div>
</main>
</body>
</html>