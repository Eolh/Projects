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

    <!-- This page -->
    <link rel="stylesheet" href="/public/css/main_header.css">
    <link rel="stylesheet" href="/public/export_modules/fullcalendar/fullcalendar.css">
    <link rel="stylesheet" href="/public/css/fullcalendar.css">
    <link rel="stylesheet" href="/public/css/fullcalendar.print.css">
    <!-- end:   CSS -->
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
    <!--================================== start: script =================================-->
    <script src="/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="/public/export_modules/jquery-ui/jquery-ui.min.js"></script>
    <script src="/public/export_modules/bootstrap/js/bootstrap.min.js"></script>

    <!-- Bootstrap Material Js -->
    <script src="/public/export_modules/dist/js/material.min.js"></script>
    <script src="/public/export_modules/dist/js/ripples.min.js"></script>

    <!-- This page -->
    <script src="/public/export_modules/fullcalendar/lib/jquery-ui.custom.min.js"></script>
    <script src="/public/export_modules/fullcalendar/lib/moment.min.js"></script>
    <script src="/public/export_modules/fullcalendar/fullcalendar.js"></script>
    <script src="/public/export_modules/fullcalendar/lang/ja.js"></script>

    <script src="/public/export_modules/jssor.slider.mini.js"></script>
    <script type="text/javascript"
            src="https://maps.google.com/maps/api/js?v=3.23&key=AIzaSyCYCSJRgNsqrQbsTQhw1FUb6FhhhZXI3y4&language=ja&libraries=places"
            async defer></script>
    <script src="/public/js/weather.js"></script>
    <!--material동작처리-->
    <script>

        var selectedMember;

        $(document).ready(function () {
            $.material.init();
        });

        $(window).resize(function () {
            var height = $('.navbar').css('height');
            $('main').css('marginTop', height);
        });

        $(document).ready(function () {

            var colorRel = []; // color rendering

            Array.prototype.shuffle = function () {

                var input = this;

                for (var i = input.length - 1 ; i >= 0 ; i--) {

                    var randomIndex = Math.floor(Math.random() * (i + 1));
                    var itemAtIndex = input[randomIndex];

                    input[randomIndex] = input[i];
                    input[i] = itemAtIndex;
                }

                return input;
            };

            var gnum = '<?=$groupList->gnum?>';
            var colorArray = ['#02BDAA', '#FF6161', '#FF9966', '#F7D148', '#DDA9FC', '#92A501', '#BCCAF4', '#C9208E', '#0D76C6', '#A25CE8'];
            colorArray.shuffle();

            var member;
            var maxCnt;
            var count  = 0;

            /* 2016-08-02 groupFullCalendar is started */
            $("#calendar").fullCalendar({
                firstDay         : 1,
                allDaySlot       : false,
                header           : {
                    left   : 'prev,next today',
                    center : 'title',
                    right  : 'month,agendaWeek,agendaDay'
                },
                minTime          : "06:00:00",
                scrollTime       : "06:00:00", //moment().format('HH:00:00'), //"09:00:00", // looking sight
                maxTime          : "24:00:00",
                defaultView      : 'agendaWeek',
                height           : 600,
                events           : '/group/getGroupFullEvents/' + gnum,
                eventLimit       : true,
                editable         : true,
                selectable       : true,
                selectHelper     : true,
                select           : function (start, end, jsEvent, view) {

                    var mEnd = $.fullCalendar.moment(end);
                    var mStart = $.fullCalendar.moment(start);

                    if (mEnd.isAfter(mStart, 'day')) {
                        window.alert("not multiple date!!! only single day!!!");
                        $('#calendar').fullCalendar('unselect');
                        return false;
                    } /* check single Day */

                    if(view.name == 'agendaWeek' || view.name == 'agendaDay') {

                        $("#uploadGroupSchedule").modal('show');

                        start = moment(start).format("YYYY-MM-DD HH:mm:ss");
                        end   = moment(end)  .format("YYYY-MM-DD HH:mm:ss");

                        $("#registerGroupScheduler #sch_f_time").attr("value", start.split(" ")[0]);
                        $("#registerGroupScheduler #sch_l_time").attr("value", end  .split(" ")[0]);

                        $("#registerGroupScheduler #sch_f_time_min").attr("value", start.split(" ")[1]);
                        $("#registerGroupScheduler #sch_l_time_min").attr("value", end  .split(" ")[1]);

                        $("a[id='scheduleAddBtn']").unbind('click');
                        $("a[id='scheduleAddBtn']").click(function() {

                            var title    = $("#title").val();
                            var content  = $("#content").val();
                            var startMin = $("#sch_f_time_min").val();
                            var endMin   = $("#sch_l_time_min").val();
                            var gm_id    = selectedMember;

                            if(!title) {
                                window.alert("タイトルを入れてください!!!");
                                return false;
                            } else if(!content) {
                                window.alert("内容を入れてください!!!");
                                return false;
                            } else if(!startMin) {
                                window.alert("始まる時間を入れてください!!!");
                                return false;
                            } else if(!endMin) {
                                window.alert("終わる時間を入れてください!!!");
                                return false;
                            } else if(!gm_id) {
                                window.alert("メンバーをチェックしてください!!!");
                                return false;
                            }

                            if(title && content) {

                                var form     = $("#registerGroupScheduler")[0];
                                var formData = new FormData(form);

                                start = $("#registerGroupScheduler #sch_f_time").val() + " " + startMin;
                                end   = $("#registerGroupScheduler #sch_l_time").val() + " " + endMin;

                                formData.append("gm_id", gm_id);
                                formData.append("start", start);
                                formData.append("end",   end);
                                formData.append("gnum",  gnum);

                                $.ajax({
                                    type        : "POST",
                                    url         : '/group/insertGroupCalendarSchedule',
                                    data        : formData,
                                    async       : false,
                                    cache       : false,
                                    contentType : false,
                                    processData : false,
                                    success     : function(json) {
                                        console.log(json);

                                        $("#calendar").fullCalendar('renderEvent', {
                                            SdNum : json,
                                            title : title,
                                            start : start,
                                            end   : end,
                                            gm_id : gm_id
                                        }, true);
                                    },
                                    error       : function(json) {

                                        $("#calendar").fullCalendar('renderEvent', {
                                            SdNum : json,
                                            title : title,
                                            start : start,
                                            end   : end,
                                            gm_id : gm_id
                                        }, true);
                                    }
                                });
                            }
                        });

                        $("#calendar").fullCalendar('unselect');

                        $("#registerGroupScheduler").closest('form').find("input[id='title'], input[id='content'], input[id='tagName'], input[id='pac-input'], input[id='sch_file_pc'], input[id='sch_file'], textarea").val("");
                    } else {
                        $("#registerGroupScheduler").closest('form').find("input[id='title'], input[id='content'], input[id='tagName'], input[id='pac-input'], input[id='sch_file_pc'], input[id='sch_file'], textarea").val("");
                        return false;
                    }
                },
                eventDrop        : function (event, delta, revertFunc, jsEvent, ui, view) { /* 2016-08-04 eventDrop drag and drop */

                    var beforeStart = event.start._i;
                    var beforeEnd = event.end._i;

                    if(!confirm("本当に変わりますか？？？")) {
                        revertFunc();
                        return false;
                    }

                    var start = moment(event.start).format("YYYY-MM-DD HH:mm:ss");
                    var end   = moment(event.end)  .format("YYYY-MM-DD HH:mm:ss");

                    $.ajax({
                        type : "POST",
                        url  : '/group/updateGroupCalendarSchedule/',
                        data : {
                            SdNum : event.SdNum,
                            start : start,
                            end : end
                        }
                    }); // update schedule's startTime, lastTime
                    $.ajax({
                        type : "POST",
                        url  : '/group/scheduleMove/',
                        data : {
                            SdNum : event.SdNum,
                            start : beforeStart,
                            end : beforeEnd,
                            gnum : gnum
                        }
                    });
                },
                eventClick       : function (date, jsEvent, view) {

                    $('#myCarousel').carousel(0);
                    var start = moment(date.start).format('YYYY-MM-DD HH:mm:ss');
                    var end   = moment(date.end)  .format('YYYY-MM-DD HH:mm:ss');

                    var file = $('<ul></ul>').addClass('list-group').html('<li class="list-group-item list-group-item-info">File List</li>');

                    $.ajax({

                        url: '/group/getgroupFile',
                        data: {
                            SdNum: date.SdNum

                        },
                        type: "POST",
                        success: function (data) {
                            data = JSON.parse(data);
                            var cnt = data.length;
                            if (cnt == 0)
                                $('<div></div>').appendTo(file).text("ファイルがない");//파일이 없습니다.
                            else {
                                for (var i = 0; i < cnt; i++) {
                                    var FNum = data[i].FNum;

                                    var Flink = data[i].Flink;
                                    var FName = data[i].FName;
                                    var hashtag = data[i].hashtag;
                                    var link = $('<a></a>').attr('href', '/public/file/group_'+gnum+'/' + FName);

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
                        url: '/group/getGroupSchedule',
                        data: {
                            SdNum: date.SdNum,
                            start: start,
                            end: end

                        },
                        type: "POST",
                        success: function (data) {
                            data = JSON.parse(data);
                            console.log(data);
                            /*위도와 경도 데이터를 가져옵니다.*/
                            if (data.latitude!=0.000000||!data.latitude) {
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
                            if(data['on/off']==0) {
                                $('.onoffswitch-checkbox').prop("checked", true);
                            }else{
                                $('.onoffswitch-checkbox').prop("checked", false);
                                $('.onoffswitch').append('<textfield>'+data.result+'</textfield>');
                            }
                            $('#schedulecontent').text(data.content);
                        }
                    });

                    /*$('.modal-header').html(date.title);
                     $('#schedulecontent').html(schedule);*/

                    console.log(date);
                    $('#scheduletitle').text(date.title);
                    $('#schedule_start').text(start);
                    $('.onoffswitch').val(date.SdNum);
                    $('#schedule_end').text(end);
                    $('#schedulefile').html(file);
                    $('#detail_schedule').modal("show");
                },
                eventResize      : function (event, delta, revertFunc, jsEvent, ui, view) { /* 2016-08-04 eventDrop drag and drop */

                    if(!confirm("本当に変わりますか？？？")) {
                        revertFunc();
                        return false;
                    }

                    var start = moment(event.start).format("YYYY-MM-DD HH:mm:ss");
                    var end   = moment(event.end)  .format("YYYY-MM-DD HH:mm:ss");

                    $.ajax({
                        type : "POST",
                        url  : '/group/updateGroupCalendarSchedule/',
                        data : {
                            SdNum : event.SdNum,
                            start : start,
                            end   : end
                        }
                    });
                },
                eventRender      : function (event, element, view) { /* 2016-08-02 color rendering is success */

                    if (typeof member === 'undefined') {
                        member = event.gm_id;
                        colorRel[event.gm_id] = colorArray[count];
                    } else if (member != event.gm_id && typeof maxCnt == "undefined") {
                        member = event.gm_id;
                        count++;
                        colorRel[event.gm_id] = colorArray[count];
                    }

                    element.find('.fc-content').css('color', colorRel[event.gm_id]);
                },
                eventAfterAllRender : function(view) {
                    if(typeof maxCnt == "undefined") {
                        maxCnt = count;
                    }
                }
            });
        });

        /* 2016-08-05 getJssor member's list */

        function getJssor() {
            var jssor_options = {
                $AutoPlay               : false,
                $SlideDuration          : 160,
                $SlideWidth             : 80,
                $SlideSpacing           : 3,
                $Cols                   : 5,
                $ArrowNavigatorOptions  : {
                    $Class : $JssorArrowNavigator$,
                    $Steps : 4
                },
                $BulletNavigatorOptions : {
                    $Class    : $JssorBulletNavigator$,
                    $SpacingX : 1,
                    $SpacingY : 1
                }
            };

            new $JssorSlider$('jssorSlider', jssor_options);
        }

        window.setTimeout(function () {
            getJssor();
        }, 200);

        /* 만약 둘의 기능이 같을 경우에는 합치자... */
        $(document).on("click", "div[id^=create_][id$=_events]", function () {

            $("div[id^=member_][id$=_events]").css("border", "");
            $("div[id^=create_][id$=_events]").css("border", "");
            $(this).css("border", "3px solid red");

            //window.alert($(this).attr('id'));
            selectedMember = ($(this).attr('id')).split("_")[2];
        });

        $(document).on("click", "div[id^=member_][id$=_events]", function () {

            $("div[id^=member_][id$=_events]").css("border", "");
            $("div[id^=create_][id$=_events]").css("border", "");
            $(this).css("border", "3px solid red");

            //window.alert($(this).attr('id'));
            selectedMember = ($(this).attr('id')).split("_")[2];
        });

    </script>
    <!-- end:   script -->
</head>
<body>
<div id="test"></div>
<?php require_once "application/views/common/header_main.php"; ?>

<main>
    <div class="container" style="padding-top: 20px;">
        <!-- Calendars -->
        <div id="calendar"></div>

        <div class="modal" id="uploadGroupSchedule" style="z-index: 1041">
            <?php require_once "application/views/group/day_emit.php" ?>
        </div>

        <div class="modal fade" id="detail_schedule">
            <?php require_once "application/views/group/emit/detail_schedule.php"; ?>
        </div>
        <!--  -->
</main>
</body>
</html>