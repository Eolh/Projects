<!DOCTYPE html>
<html>
<head>
    <!-- start: PAGE SETTINGS -->
    <title> it's a FullCalendar Test!!! </title>
    <meta charset="utf-8">
    <!-- end:   PAGE SETTINGS -->

    <!-- start: CSS -->
    <link rel="stylesheet" href="http://localhost:8088/public/export_modules/fullcalendar/fullcalendar.css">
    <link rel="stylesheet" href="http://localhost:8088/public/export_modules/bootstrap/css/bootstrap.css">
    <style>
        img
        {
            width   : 25px; height  : 25px; padding : 1px; margin  : 1px;
        }
    </style>
    <!-- end:   CSS -->

    <!-- start: script -->
    <script src="http://localhost:8088/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="http://localhost:8088/public/export_modules/jquery-ui/jquery-ui.min.js"></script>
    <script src="http://localhost:8088/public/export_modules/fullcalendar/lib/moment.min.js"></script>
    <script src="http://localhost:8088/public/export_modules/fullcalendar/lib/jquery-ui.custom.min.js"></script>
    <script src="http://localhost:8088/public/export_modules/fullcalendar/fullcalendar.js"></script>
    <script src="http://localhost:8088/public/export_modules/fullcalendar/lang/AAtest.js"></script>
    <script src="http://localhost:8088/public/export_modules/bootstrap/js/bootstrap.min.js"></script>

    <script>
        /* Group Calendar의 경우 여러개의 달력을 만들어서 가리는 방법으로 야매(?)가 가능하다. */

        var calendar = [];

        var temper = <?=json_encode($groupInfo);?>;
        var tempOption = "";
        var tempSelect, tempStoreSelect;
        var temperBefore, temperAfter;

        var optionHandler = <?=json_encode($groupInfo[0]->UCNum)?>;

        temperBefore = "<select name='groupCalendar' id='groupCal' onchange='foo()'>";
        temperAfter  = "</select>";

        console.log(optionHandler);

        for (var i in temper) {
            tempOption += "<option id='" + temper[i].UCNum + "' onclick='foo2()'>" + temper[i].calName + "</optrion>";
        }

        tempSelect = temperBefore + tempOption + temperAfter;

        $(document).ready(function () {

            /* 이 부분을 바꿀 방법이 없나??? */
            <?php for($x=0 ; $x<count($calendarList) ; $x++) { ?>
            setCalendar("<?=$calendarList[$x][0]->UCNum?>", "#calendar<?=$calendarList[$x][0]->UCNum?>", <?=json_encode($calendarList[$x])?>);
            <?php if($x==0) { ?>
            $("#calendar<?=$calendarList[$x][0]->UCNum?>").show();
            <?php } else { ?>
            $("#calendar<?=$calendarList[$x][0]->UCNum?>").hide();
            <?php }
            } ?>

            $("#calendar" + optionHandler + " .fc-left").append(tempSelect);
            /* 기본 view Setting */
        });

        function foo()
        {
            if ($("#groupCal option:selected").attr('id') == "register") {
                tempStoreSelect = optionHandler;
            }
            optionHandler = $("#groupCal option:selected").attr('id');

            $("main div:visible select").remove();
            $("main > div:visible").hide();
            /* >이게 하나만 가리킨다... */
            /* main div:visible 이거는 성립하는 모든 자손을 가리킨다. */
            $("#calendar" + optionHandler + " .fc-left").append(tempSelect);
            $("#groupCal #" + optionHandler).attr("selected", "selected");
            $("#groupCalRegister #" + optionHandler + "Register").attr("selected", "selected");
            $("#calendar" + optionHandler).show();
        }

        function setCalendar(calendarNum, selector, callData) {

            calendar[calendarNum] = $(selector).fullCalendar({
                /* start: setting */
                /*defaultView : "agendaDay",*/
                //theme          : false,
                firstDay      : 0, /* 날짜의 기준일 0: sunday */
                customButtons : {
                    myCustomButton : {
                        text  : 'custom!',
                        click : function () {

                            $('#uploadGroup').modal('show');

                            $("a[id='groupAddBtn']").unbind('click');
                            $("a[id='groupAddBtn']").click(function () {

                                var check = true;
                                var groupName = $("#groupName").val();

                                if(!groupName)
                                    return false;

                                $("#groupCal option").each(function() {
                                    //console.log($(this).text());
                                    if(groupName == $(this).text())
                                    {
                                        window.alert("이미있습니다.");
                                        check = false;
                                    }
                                });

                                if(!check) {
                                    check = true;
                                    return false;
                                }

                                var form  = $("#registerGroup")[0];
                                var formData = new FormData(form);

                                $.ajax({
                                    type        : "POST",
                                    url         : 'http://localhost:8088/ajaxHandler/insertGroup',
                                    data        : formData,
                                    async       : false,
                                    cache       : false,
                                    contentType : false,
                                    processData : false,
                                    success     : function (json) {
                                        //alert(json);
                                        //console.log(json);

                                        console.log(json);
                                        good = JSON.parse(json);
                                        //console.log(good);
                                        $("#uploadSchedule").before("<div id='calendar" + good.UCNum + "'>" + "</div>");
                                        var addOption = "<option id='" + good.UCNum + "' onclick='foo2()'>" + good.calName + "</optrion>"
                                        $("#groupCal").append(addOption);
                                        tempSelect = temperBefore + tempOption + addOption + temperAfter;
                                        $("#groupCalRegister").append("<option id='" + good.UCNum  + "Register'>" + good.calName + "</option>");
                                        setCalendar(good.UCNum, "#calendar" + good.UCNum, [{"title":"","start":"0000-00-00","end":"","UCNum":good.UCNum}]);
                                        $("#calendar" + good.UCNum).hide();
                                        //return false;
                                        /* 이제 이 값을 SdNum를 넣자... */
                                    },
                                    error       : function (json) {
                                        //alert(json);
                                        $("#test").append(json);
                                        //return false;
                                    }
                                });
                            });
                        }
                    }
                },
                header        : {
                    left   : 'prev,next today myCustomButton',
                    center : 'title',
                    right  : 'month,agendaWeek,agendaDay'
                },
                editable      : true,
                /* end: setting */
                /* start: select */
                selectable    : true,
                selectHelper  : true,
                select        : function (start, end, jsEvent, view) {

                    $('#uploadSchedule').modal('show');

                    start = moment(start).format('YYYY-MM-DD HH:mm:ss');
                    end = moment(end).format('YYYY-MM-DD HH:mm:ss');

                    if (start.split(" ")[1] == "00:00:00" && end.split(" ")[1] == "00:00:00") {
                        start = moment(start).format('YYYY-MM-DD');
                        end = moment(end).format('YYYY-MM-DD');
                        $("#sch_f_time").attr("value", "");
                        $("#sch_l_time").attr("value", "");
                    } else {
                        start = moment(start).format('YYYY-MM-DD HH:mm:ss');
                        end = moment(end).format('YYYY-MM-DD HH:mm:ss');
                        $("#sch_f_time").attr("value", start.split(" ")[1]);
                        $("#sch_l_time").attr("value", end.split(" ")[1]);
                    }
                    //window.alert(start + " " + end + " " + view.type);

                    //var title = prompt('Sample Textbox: <textarea>ssibal</textarea>');
                    $("a[id='scheduleAddBtn']").unbind('click');
                    $("a[id='scheduleAddBtn']").click(function () {

                        var title = $("#title").val();
                        var content = $("#content").val();
                        var SdNum;

                        if (!title) {
                            window.alert("TITLE!!!");
                            return false;
                        } else if (!content) {
                            window.alert("CONTENT!!!");
                            return false;
                        }

                        if (title && content) {

                            console.log(start + " " + end + " " + calendarNum);

                            var form  = $("#registerScheduler")[0];
                            var formData = new FormData(form);
                            var groupCalRegister = $("#groupCalRegister option:selected").attr("id");

                            window.alert(groupCalRegister);

                            formData.append("UCNum", calendarNum);
                            formData.append("start", start);
                            formData.append("end", end);
                            formData.append("groupCalRegisterOption", groupCalRegister);

                            $.ajax({
                                type        : "POST",
                                url         : 'http://localhost:8088/ajaxHandler/insertCalendarSchedule',
                                data        : formData,
                                async       : false,
                                cache       : false,
                                contentType : false,
                                processData : false,
                                success     : function (json) {
                                    //alert(json);
                                    //console.log(json);
                                    $("#test").append(json);
                                    //return false;
                                    /* 이제 이 값을 SdNum를 넣자... */
                                    SdNum = json;

                                    //calendar[calendarNum].fullCalendar('renderEvent', {
                                    calendar[calendarNum].fullCalendar('renderEvent', {
                                            SdNum  : SdNum,
                                            title  : title,
                                            start  : start,
                                            end    : end,
                                            allDay : jsEvent,
                                            UCNum  : calendarNum
                                        }, true // make the event "stick"
                                    );
                                },
                                error       : function (json) {
                                    //alert(json);
                                    $("#test").append(json);
                                    //return false;

                                    //calendar[calendarNum].fullCalendar('renderEvent',
                                    calendar[calendarNum].fullCalendar('renderEvent',
                                        {
                                            SdNum  : json,
                                            title  : title,
                                            start  : start,
                                            end    : end,
                                            allDay : jsEvent,
                                            UCNum  : calendarNum
                                        },
                                        true // make the event "stick"
                                    );
                                }
                            });
                        }
                    });
                    calendar[calendarNum].fullCalendar('unselect');

                    $("#title").val("");
                    $("#content").val("");
                    $("#tagName").val("");
                    $("#sch_file_pc").val("");
                },
                /* end: select */
                /* start: events  */
                eventLimit    : true,
                eventMouseover : function () {
                    $(this).css("background-color", "purple")
                }, /* 마우스를 일정에 올리면 */
                eventMouseout  : function (event, jsEvent, view) {
                    $(this).css("background-color", "green")
                }, /* 마우스를 일정에서 때면 */
                /* end: events */
                eventDrop      : function (event, delta, revertFunc, jsEvent, ui, view) {

                    if (!confirm("바꿀?")) {
                        revertFunc();
                        return false;
                    }

                    var start = moment(event.start).format('YYYY-MM-DD HH:mm:ss');
                    var end = moment(event.end).format('YYYY-MM-DD HH:mm:ss');

                    if (end == "Invalid date" && event.allDay == true) {
                        start = moment(event.start).format('YYYY-MM-DD');
                        var parts = start.split("-");
                        end = parts[0] + "-" + parts[1] + "-" + (parseInt(parts[2]) + 1);
                    } else if (end == "Invalid date" && event.allDay == false) {
                        start = moment(event.start).format('YYYY-MM-DD HH:mm:ss');
                        parts = start.split(" ");
                        var parts2 = parts[1].split(":");
                        end = parts[0] + " " + (parseInt(parts2[0]) + 2) + ":" + parts2[1] + ":" + parts2[2];
                    } else if (start.split(" ")[1] == "00:00:00" && end.split(" ")[1] == "00:00:00") {
                        start = moment(event.start).format('YYYY-MM-DD');
                        parts = start.split("-");
                        end = parts[0] + "-" + parts[1] + "-" + (parseInt(parts[2]) + 1);
                    }

                    window.alert(start + " " + end + " " + event.allDay);

                    $.ajax({
                        url   : 'http://localhost:8088/ajaxHandler/updateCalendarScheduleToDrop',
                        data  : {
                            title : event.title,
                            start : start,
                            end   : end,
                            id    : event.id,
                            SdNum : event.SdNum
                        },
                        type  : "POST",
                        error : function (error) {
                            window.alert(error);
                        }
                    });
                },
                /*eventResizeStop  : function (event, jsEvent, ui, view) {
                 var start = moment(event.start).format('YYYY-MM-DD HH:mm:ss');
                 var end   = moment(event.end).format('YYYY-MM-DD HH:mm:ss');
                 start     = moment(view.start).format('YYYY-MM-DD HH:mm:ss');
                 end       = moment(view.end).format('YYYY-MM-DD HH:mm:ss');
                 //console.log(start + " " + end);
                 },*/
                eventResize    : function (event, delta, revertFunc, jsEvent, ui, view) {
                    if (!confirm("???")) {
                        revertFunc();
                        return false;
                    } else {
                        var start = moment(event.start).format('YYYY-MM-DD HH:mm:ss');
                        var end = moment(event.end).format('YYYY-MM-DD HH:mm:ss');
                        window.alert(
                            "SdNum : " + event.SdNum + " " +
                            "start : " + start + " " +
                            "end : " + end + " "
                        );

                        $.ajax({
                            url   : 'http://localhost:8088/ajaxHandler/updateCalendarScheduleToResize',
                            data  : {
                                title : event.title,
                                start : start,
                                end   : end,
                                id    : event.id,
                                SdNum : event.SdNum
                            },
                            type  : "POST",
                            error : function (error) {
                                window.alert(error);
                            }
                        });
                        /* 이걸로 계산합니다! */
                    }
                },
                eventClick : function(date, jsEvent, view) {

                    alert("sexKing" );
                }
            });

            if(callData[0].title != "")
            {
                calendar[calendarNum].fullCalendar('addEventSource', callData);
            }/* 전부 이렇게 나누어 두자능... */
            /* ID가 같을 경우에는 같이 움직임ㅋ group ID */
        }

    </script>
    <!-- end:   script -->
</head>
<body>
<header>
    <?php require_once "application/views/common/header_main.php"; ?>
</header>

<div id="test">

</div>

<main>
    <!-- Calendars -->
    <?php /*getArgDump($calendarList); */ ?>
    <?php for ($x = 0; $x<count($calendarList); $x++)
    { ?>
        <div id='calendar<?= $calendarList[ $x ][ 0 ]->UCNum ?>'></div>
    <?php } ?>

    <!-- Calendar Scheduler -->
    <div class="modal fade" id="uploadSchedule">
        <?php require_once "application/views/calendar/emit/day_emit.php"; ?>
    </div>

    <div class="modal fade" id="uploadGroup">
        <?php require_once "application/views/calendar/group/group_add.php"; ?>
    </div>
</main>
</body>
</html>