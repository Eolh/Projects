var maps;
var markers = [];

function init(lat, lon) {
//            latlog = new google.maps.LatLng(lat,lon);
    var myOptions = {
        maxZoom   : 16,
        zoom      : 15,
        center    : {lat : lat, lng : lon},
        mapTypeId : google.maps.MapTypeId.ROADMAP
    };
    maps = new google.maps.Map(document.getElementById("maps"), myOptions);
}

$(document).ready(function () { //testWeather()
    navigator.geolocation.getCurrentPosition(function (position) {

        lat = position.coords.latitude;
        lon = position.coords.longitude;

        accuweather(lat, lon, 1);
        briefing(lat, lon);
        window.onload = init(lat, lon);
    });

    /*timer, clock*/
    function startTime() {
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('clock').innerHTML = h + ":" + m + ":" + s;
        var t = setTimeout(startTime, 500);
    }

    function checkTime(i) {
        if (i < 10) {
            i = "0" + i
        }
        ; // 숫자가 10보다 작을 경우 앞에 0을 붙여줌
        return i;
    }

    function briefing(lat, lon) {
        var today = new Date();
        var day = today.getDate();
        var month = today.getMonth() + 1;
        if (month < 10) {
            month = '0' + month;
        }
        var year = today.getFullYear();
        var current_date = year + "-" + month + "-" + day;

        $('#briefing1').html(current_date + " , <span id='b_location'></span>&nbsp;<span id='b_weather_name'></span>&nbsp;<span id='b_temp'></span>");
        startTime();

        $.ajax({
            url     : '/main/getBriefing',
            data    : {},
            type    : 'POST',
            success: function (data) {
                data = JSON.parse(data);
                if (data.schedule[0]) {
                    $('<span></span>').css("font-size", "2.5vmax").addClass("animated flipInX").text(data.schedule[0].startTime + " | " + data.schedule[0].title + " , " + data.schedule[0].place_name).appendTo('#briefing2');
                } else {
                    $('<span></span>').css("font-size", "2.5vmax").addClass("animated flipInX").text("日程がありません").appendTo('#briefing2');
                }
                if (data.schedule[1]) {
                    $('#briefing2').append("<p>次の日程 - " + data.schedule[1].startTime + " | " + data.schedule[1].title + " , " + data.schedule[1].place_name + " [ " + data.count[0].leftSche + " / " + data.count[1].countSche + " ]</p>");
                    $('#briefing3').css("font-size", "21px").append("<span><i class='material-icons'>check_box</i> " + data.todo.leftTodo + " 個<br><i class='material-icons'>insert_emoticon</i> 午後、雨、傘が必要</span>");
                } else {
                    $('#briefing2').append("<p>次の日程 - 日程がありません [ " + data.count[0].leftSche + " / " + data.count[1].countSche + " ]</p>");
                    $('#briefing3').css("font-size", "21px").append("<span><i class='material-icons'>check_box</i> " + data.todo.leftTodo + " 個<br><i class='material-icons'>insert_emoticon</i> 午後、雨、傘が必要</span>");
                }
            }
        });
    }

    $.ajax({
        type    : 'POST',
        url     : '/TodoList/ListTodo', //NoteTodo
        data    : {},
        success : function (html) {
            $("#TodoList2").html(html).show();
        }
    });

    $("#monthlyCalendar").fullCalendar({
        dayClick    : function (date, allDay, jsEvent, view) {

            var day;
            day = date.format("YYYY-MM-DD");

            $("#todayCalendar").fullCalendar('gotoDate', date);

            $.ajax({

                type    : 'POST',
                url     : '/TodoList/dashboardTodo',
                data    : {'start_day' : day},
                success : function (data) {

                    $(".dashboardtodo").replaceWith(data);
                }
            });

            $.ajax({
                type    : "POST",
                url     : '/main/loadplaces',
                data    : {
                    date : day
                },
                success : function (data) {

                    if (markers) {
                        for (i = 0 ; i < markers.length ; i++) {
                            markers[i].setMap(null);
                        }
                        markers.length = 0;
                    }

                    var placedata = JSON.parse(data);
                    var bounds = new google.maps.LatLngBounds(); //  all marker show on map
                    var locations = [];
                    for (var i = 0 ; i < placedata.length ; i++) {
                        if (placedata[i].latitude != 0.000000) {
                            locations.push({
                                name   : placedata[i].title,
                                latlng : new google.maps.LatLng(placedata[i].latitude, placedata[i].longitude)

                            });
                        }
                    }
                    for (var i = 0 ; i < placedata.length ; i++) {
                        if (placedata[i].latitude != 0.000000) {
                            markers[i] = new google.maps.Marker({
                                position : locations[i].latlng,
                                map      : maps,
                                title    : placedata[i].title
                            });

                            bounds.extend(locations[i].latlng);
                        }
                    }
                    maps.setCenter(bounds.getCenter());
                    maps.fitBounds(bounds);  //  all marker show on map
                }
            });

            $.ajax({
                type    : "POST",
                url     : '/main/getFileListByDate',
                data    : {"date" : day},
                success : function (json) {

                    $("#fileList").empty();
                    $("#fileList").append(json);
                }
            });

        },
        eventClick  : function (date, jsEvent, view) {

            $("#todayCalendar").fullCalendar('gotoDate', date._start._i);
            $.ajax({
                type    : "POST",
                url     : '/main/loadplaces',
                data    : {
                    date : date._start._i
                },
                success : function (data) {

                    $.ajax({

                        type    : 'POST',
                        url     : '/TodoList/dashboardTodo',
                        data    : {'start_day' : date._start._i},
                        success : function (data) {

                            $(".dashboardtodo").replaceWith(data);
                        }
                    });

                    if (markers) {
                        for (i = 0 ; i < markers.length ; i++) {
                            markers[i].setMap(null);
                        }
                        markers.length = 0;
                    }

                    var placedata = JSON.parse(data);
                    var bounds = new google.maps.LatLngBounds(); //  all marker show on map
                    var locations = [];
                    for (var i = 0 ; i < placedata.length ; i++) {
                        if (placedata[i].latitude != 0.000000) {
                            locations.push({
                                name   : placedata[i].title,
                                latlng : new google.maps.LatLng(placedata[i].latitude, placedata[i].longitude)

                            });
                        }
                    }
                    for (var i = 0 ; i < placedata.length ; i++) {
                        if (placedata[i].latitude != 0.000000) {
                            markers[i] = new google.maps.Marker({
                                position : locations[i].latlng,
                                map      : maps,
                                title    : placedata[i].title
                            });

                            bounds.extend(locations[i].latlng);
                        }
                    }
                    maps.setCenter(bounds.getCenter());
                    maps.fitBounds(bounds);  //  all marker show on map
                }
            });
            $.ajax({
                type    : "POST",
                url     : '/main/getFileListByDate',
                data    : {"date" : date._start._i},
                success : function (json) {

                    $("#fileList").empty();
                    $("#fileList").append(json);
                }
            });
        },
        dayRender   : function (date, cell) {

            $.ajax({
                type    : "POST",
                url     : '/main/getExistDate',
                success : function (json) {
                    var calendarData = JSON.parse(json);

                    //alert(calendarData);
                    //console.log(typeof calendarData);
                    //console.log(calendarData);

                    for (var i in calendarData) {

                        $('#monthlyCalendar .fc-day[data-date="' + calendarData[i].date + '"]').addClass('cyka');
                    }

                }
            });
        },
        events      : "/main/getExistFile",
        eventRender : function (event, element, view) {

            //if (event.img) {
            $(element.context)
                .css("border-color", "transparent")
                .css("background-color", "transparent")
                .css("color", "#577cbd")
                .html("<i style='width: 50%; height: 50%;' class='fa fa-file-o fa-2x'><span class='badge'>" + event.cnt + "</span></i>");
            //}
        }
    });

    $("#todayCalendar").fullCalendar({
        defaultView : "agendaDay",
        minTime     : "06:00:00",
        scrollTime  : '09:00:00',
        height      : 800,
        events      : "/main/getCalendarData",
        allDaySlot  : false,
        eventClick  : function (date, jsEvent, view) {

            $.ajax({
                type    : "POST",
                url     : '/main/getFileListBySdNum',
                data    : {"SdNum" : date.SdNum},
                success : function (json) {
                    $("#fileList").empty();
                    $("#fileList").append(json);
                }
            });
        }
    });

    $.ajax({
        type    : "POST",
        url     : '/main/getFileListByDate',
        success : function (json) {

            if (json) {
                $("#fileList").empty();
                $("#fileList").append(json);
            }
        }
    });

    /* 2016-07-26 GROUP */

    $('.member_caption').hover(function() {
        $(this).slideUp(200);
    },function(){
        $(this).slideDown(200);
    });

    getGroupList();

    function getGroupList() {

        $.ajax({
            type    : "POST",
            url     : "/main/getGroupTabList",
            success : function (json) {

                if (json) {
                    getGroupDashBoard(json);
                }
            }
        });
    }

    function getGroupDashBoard(groupList) {
        $.ajax({
            type    : "POST",
            url     : "/main/getGroupDashBoard",
            success : function (json) {

                $("#tabList ul li:nth-child(1)").after(groupList);
                $("#home").after(json);
            }
        });
    }

    $(document).on("click", "#tabList ul li", function () {

        var gnum = $(this).attr("id");

        getGroupFullCalendar(gnum);
        getJssorSlider(gnum);
        getGroupFile(gnum, false);

        window.setTimeout(function () {
            $(".fc-today-button").click();
            getGroupmaps(gnum);
        }, 200);
    });

    var colorRel = []; // member's color setting by click #tabList ul li -> getGroupFullCalendar

    Array.prototype.shuffle = function() {

        var input = this;

        for(var i=input.length-1 ; i>=0 ; i--) {

            var randomIndex = Math.floor(Math.random()*(i+1));
            var itemAtIndex = input[randomIndex];

            input[randomIndex] = input[i];
            input[i] = itemAtIndex;
        }

        return input;
    };

    /* 2016-09-21 */

    function getGroupFile(gnum, maOrMe) /* maOrMe is UID or master's false */
    {
        console.log(maOrMe);

        if(maOrMe) {

            $.ajax({
                type    : "POST",
                url     : "/main/getGroupFileList/" + gnum,
                data    : {
                    "gm_id" : maOrMe
                },
                success : function (json) {

                    if (json) {
                        $("#group_fileList").empty();
                        $("#group_fileList").append(json);
                    }
                }
            })
        } else {

            $.ajax({
                type    : "POST",
                url     : "/main/getGroupFileList/" + gnum,
                success : function (json) {

                    if (json) {
                        $("#group_fileList").empty();
                        $("#group_fileList").append(json);
                    }
                }
            });
        }
    }

    function getGroupFullCalendar(gnum) {

        var colorArray  = ['#02BDAA', '#FF6161', '#FF9966', '#F7D148', '#DDA9FC', '#92A501', '#BCCAF4', '#C9208E', '#0D76C6', '#A25CE8'];
        colorArray.shuffle();
        var member;
        var maxCnt;
        var count       = 0;

        $("#tabDashboardCalendar" + gnum).fullCalendar({
            defaultView      : "basicWeek",
            firstDay         : 1,
            minTime          : "06:00:00",
            scrollTime       : "09:00:00", // looking sight
            maxTime          : "24:00:00",
            allDaySlot       : false,      // allDay off
            slotDuration     : "00:30:00", // default
            slotEventOverlap : true,       // default overlad needed
            height           : 300,
            events           : "/main/getGroupFullEvents/" + gnum,
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
    }

    var JssorOverlapping = []; // stop overlapping

    function getJssorSlider(gnum) {
        var jssor_options = {
            $AutoPlay               : false,
            $SlideDuration          : 160,
            $SlideWidth             : 150,
            $SlideSpacing           : 3,
            $Cols                   : 8,
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
        if(typeof (JssorOverlapping[gnum]) === 'undefined')
            JssorOverlapping[gnum] = true;
        if(JssorOverlapping[gnum]) {
            new $JssorSlider$('jssorSlider' + gnum, jssor_options);
            JssorOverlapping[gnum] = false;
        }
    }

    $(document).on("click", "div[id^=create_][id$=_events]", function () {

        var gnum = $(this).attr('id').split('_')[1];

        getGroupFile(gnum, false);

        $("div[id^=member_][id$=_events]").css("border", "");
        $("div[id^=create_][id$=_events]").css("border", "");
        $(this).css("border", "3px solid #577cbd").addClass("stop");

        $("#tabDashboardCalendar" + gnum).remove();
        $("#calendarDIV_" + gnum).append("<div id='tabDashboardCalendar" + gnum + "' style='display: block; position: relative; margin-top: 150px'></div>");

        $("#tabDashboardCalendar" + gnum).fullCalendar({
            defaultView      : "basicWeek",
            firstDay         : 1,
            minTime          : "06:00:00",
            scrollTime       : "09:00:00", // looking sight
            maxTime          : "24:00:00",
            allDaySlot       : false,      // allDay off
            slotDuration     : "00:30:00", // default
            slotEventOverlap : true,       // default overlad needed
            height           : 300,
            events           : "/main/getMasterFullEvents/" + gnum,
            eventRender      : function(event, element, view) { /* 2016-08-02 color rendering is success */

                element.find('.fc-content').css('color', colorRel[event.gm_id]);
            }
        });
    });

    $(document).on("click", "div[id^=member_][id$=_events]", function () {

        var gnum = $(this).attr('id').split('_')[1];
        var uid = $(this).attr('id').split('_')[2];

        getGroupFile(gnum, uid);

        $("div[id^=member_][id$=_events]").css("border", "");
        $("div[id^=create_][id$=_events]").css("border", "");
        $(this).css("border", "3px solid #00c5cc");
        $(".kaitenn").removeClass("stop");

        $("#tabDashboardCalendar" + gnum).remove();
        $("#calendarDIV_" + gnum).append("<div id='tabDashboardCalendar" + gnum + "' style='display: block; position: relative; margin-top: 150px'></div>");

        $("#tabDashboardCalendar" + gnum).fullCalendar({
            defaultView      : "basicWeek",
            firstDay         : 1,
            minTime          : "06:00:00",
            scrollTime       : "09:00:00", // looking sight
            maxTime          : "24:00:00",
            allDaySlot       : false,      // allDay off
            slotDuration     : "00:30:00", // default
            slotEventOverlap : true,       // default overlad needed
            height           : 300,
            events           : "/main/getMemberFullEvents/" + gnum + "/" + uid,
            eventRender      : function(event, element, view) { /* 2016-08-02 color rendering is success */

                element.find('.fc-content').css('color', colorRel[event.gm_id]);
            }
        });
    });

    function getGroupmaps(gnum){

        var myOptions = {
            maxZoom   : 16,
            zoom      : 15,
            center    : {lat : lat, lng : lon},
            mapTypeId : google.maps.MapTypeId.ROADMAP
        };

        groupmap = new google.maps.Map(document.getElementById("group_maps"+gnum), myOptions);

    }

});