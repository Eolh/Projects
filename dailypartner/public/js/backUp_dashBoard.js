var maps;
var markers = [];

function init(lat, lon) {
//            latlog = new google.maps.LatLng(lat,lon);
    var myOptions = {
        maxZoom: 16,
        zoom: 15,
        center: {lat: lat, lng: lon},
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    maps = new google.maps.Map(document.getElementById("maps"), myOptions);
}

$('#myTabs a').click(function (e) {
    e.preventDefault()
    $(this).tab('show')
})
/*
$('#myTabs a[href="#profile"]').tab('show') // Select tab by name
$('#myTabs a:first').tab('show') // Select first tab
$('#myTabs a:last').tab('show') // Select last tab
$('#myTabs li:eq(2) a').tab('show') // Select third tab (0-indexed)
*/

$(document).ready(function () { //testWeather()
    navigator.geolocation.getCurrentPosition(function (position) {

        lat = position.coords.latitude;
        lon = position.coords.longitude;

        accuweather(lat, lon, 1);
        briefing(lat, lon);
        window.onload = init(lat, lon);
    });

    function briefing(lat, lon) {
        var today = new Date();
        var day = today.getDate();
        var month = today.getMonth() + 1;
        if (month < 10) {
            month = '0' + month;
        }
        var year = today.getFullYear();
        var current_date = year + "-" + month + "-" + day;
        $('<span></span>').css("font-size", "2.0em").html(current_date + " , <span id='b_location'></span>&nbsp;<span id='b_weather_name'></span>&nbsp;<span id='b_temp'></span>").appendTo('.briefing');
        $.ajax({
            url: '/main/getBriefing',
            data: {},
            type: 'POST',
            success: function (data) {
                data = JSON.parse(data);
                if (data.schedule[0]) {
                    $('<h1></h1>').addClass("animated flipInX").text(data.schedule[0].startTime + " | " + data.schedule[0].title + " , " + data.schedule[0].place_name).appendTo('.briefing');
                } else {
                    $('<h1></h1>').addClass("animated flipInX").text("예정된 일정이 없습니다.").appendTo('.briefing');

                }
                if(data.schedule[1]) {
                    $('.briefing').append("<p>바로 다음 일정 - " + data.schedule[1].startTime + " | " + data.schedule[1].title + " , " + data.schedule[1].place_name + "<br>현재 남은 일정 - " + data.count[0].leftSche + "개 [전체 일정 " + data.count[1].countSche + "개]<br>To-Do List - " + data.todo.leftTodo + " 개<br>그 외 필요한 정보 - 오후 비, 우산 필요</p>");
                }else {
                    $('.briefing').append("<p>바로 다음 일정 - 예정된 일정이 없습니다.<br>현재 남은 일정 - " + data.count[0].leftSche + "개 [전체 일정 " + data.count[1].countSche + "개]<br>To-Do List - " + data.todo.leftTodo + " 개<br>그 외 필요한 정보 - 오후 비, 우산 필요</p>");
                }

            }
        });
    }

    $.ajax({
        type: 'POST',
        url: '/TodoList/ListTodo', //NoteTodo
        data: {},
        success: function (html) {
            $("#TodoList2").html(html).show();
        }
    });

    $("#monthlyCalendar").fullCalendar({
        dayClick: function (date, allDay, jsEvent, view) {

            var day;
            day = date.format("YYYY-MM-DD");

            $("#todayCalendar").fullCalendar('gotoDate', date);

            $.ajax({

                type: 'POST',
                url: '/TodoList/dashboardTodo',
                data: {'start_day': day},
                success: function (data) {

                    $(".dashboardtodo").replaceWith(data);
                }
            });


            $.ajax({
                type: "POST",
                url: '/main/loadplaces',
                data: {
                    date: day
                },
                success: function (data) {

                    if (markers) {
                        for (i = 0; i < markers.length; i++) {
                            markers[i].setMap(null);
                        }
                        markers.length = 0;
                    }

                    var placedata = JSON.parse(data);
                    var bounds = new google.maps.LatLngBounds(); //  all marker show on map
                    var locations = [];
                    for (var i = 0; i < placedata.length; i++) {
                        if (placedata[i].latitude != 0.000000) {
                            locations.push({
                                name: placedata[i].title,
                                latlng: new google.maps.LatLng(placedata[i].latitude, placedata[i].longitude)

                            });
                        }
                    }
                    for (var i = 0; i < placedata.length; i++) {
                        if (placedata[i].latitude != 0.000000) {
                            markers[i] = new google.maps.Marker({
                                position: locations[i].latlng,
                                map: maps,
                                title: placedata[i].title
                            });

                            bounds.extend(locations[i].latlng);
                        }
                    }
                    maps.setCenter(bounds.getCenter());
                    maps.fitBounds(bounds);  //  all marker show on map
                }
            });

            $.ajax({
                type: "POST",
                url: '/main/getFileListByDate',
                data: {"date": day},
                success: function (json) {

                    $("#fileList").empty();
                    $("#fileList").append(json);
                }
            });

        },
        eventClick: function (date, jsEvent, view) {

            $("#todayCalendar").fullCalendar('gotoDate', date._start._i);
            $.ajax({
                type: "POST",
                url: '/main/loadplaces',
                data: {
                    date: date._start._i
                },
                success: function (data) {

                    $.ajax({

                        type: 'POST',
                        url: '/TodoList/dashboardTodo',
                        data: {'start_day': date._start._i},
                        success: function (data) {

                            $(".dashboardtodo").replaceWith(data);
                        }
                    });

                    if (markers) {
                        for (i = 0; i < markers.length; i++) {
                            markers[i].setMap(null);
                        }
                        markers.length = 0;
                    }

                    var placedata = JSON.parse(data);
                    var bounds = new google.maps.LatLngBounds(); //  all marker show on map
                    var locations = [];
                    for (var i = 0; i < placedata.length; i++) {
                        if (placedata[i].latitude != 0.000000) {
                            locations.push({
                                name: placedata[i].title,
                                latlng: new google.maps.LatLng(placedata[i].latitude, placedata[i].longitude)

                            });
                        }
                    }
                    for (var i = 0; i < placedata.length; i++) {
                        if (placedata[i].latitude != 0.000000) {
                            markers[i] = new google.maps.Marker({
                                position: locations[i].latlng,
                                map: maps,
                                title: placedata[i].title
                            });

                            bounds.extend(locations[i].latlng);
                        }
                    }
                    maps.setCenter(bounds.getCenter());
                    maps.fitBounds(bounds);  //  all marker show on map
                }
            });
            $.ajax({
                type: "POST",
                url: '/main/getFileListByDate',
                data: {"date": date._start._i},
                success: function (json) {

                    $("#fileList").empty();
                    $("#fileList").append(json);
                }
            });
        },
        dayRender: function (date, cell) {

            $.ajax({
                type: "POST",
                url: '/main/getExistDate',
                success: function (json) {

                    var calendarData = JSON.parse(json);

                    for (var i in calendarData) {

                        $('#monthlyCalendar .fc-day[data-date="' + calendarData[i].date + '"]').addClass('cyka');
                    }

                }
            });
        },
        events: "/main/getExistFile",
        eventRender: function (event, element, view) {

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
        defaultView: "agendaDay",
        scrollTime: '09:00:00',
        events: "/main/getCalendarData",
        allDaySlot: false,
        eventClick: function (date, jsEvent, view) {

            $.ajax({
                type: "POST",
                url: '/main/getFileListBySdNum',
                data: {"SdNum": date.SdNum},
                success: function (json) {
                    $("#fileList").empty();
                    $("#fileList").append(json);
                }
            });
        }
    });

    $.ajax({
        type: "POST",
        url: '/main/getFileListByDate',
        success: function (json) {

            if (json) {
                $("#fileList").empty();
                $("#fileList").append(json);
            }
        }
    });

    /* 2016-07-26 GROUP */

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
                $("#privateDboard").after(json);
                $("#tabDashboardCalendar1").fullCalendar({});
            }
        });
    }

    $(document).on('click', '#clicker', function() {

        window.alert("?");
        $("#tabDashboardCalendar1").fullCalendar('today');
    });
});