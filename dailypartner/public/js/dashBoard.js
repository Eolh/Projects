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

function autoRegisterAndAppend() {
    var form = $("#insertAuto")[0];
    var formData = new FormData(form);
    $.ajax({
        type: "POST",
        url: "/AndroidHandler/insertParsing",
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (json) {

            console.log(json);

            $("#autoLister tbody").append(json);
        }
    })
}

$.ajax({
    type: "POST",
    url: "/AndroidHandler/getAutoList",
    success: function (json) {
        $("#autoLister tbody").append(json);
    }
});

$.ajax({
    type: "POST",
    url: "/AndroidHandler/getMultiCalendar",
    success: function (json) {

        var data = JSON.parse(json);
        var tempOption = "";

        console.log(data);

        for (var i = 0; i < data.length; i++) {
            if (i == 0) {
                tempOption += "<option selected value='" + data[i].UCNum + "'>" + data[i].calName + "</option>";
            } else {
                tempOption += "<option value='" + data[i].UCNum + "'>" + data[i].calName + "</option>";
            }
        }

        $("#multiCalendar").append(tempOption);
    }
});

function autoRegister() {
    $("#autoList").modal("show");
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
            url: '/main/getBriefing',
            data: {},
            type: 'POST',
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

                    //alert(calendarData);
                    //console.log(typeof calendarData);
                    //console.log(calendarData);

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
        minTime: "06:00:00",
        scrollTime: '09:00:00',
        height: 800,
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
            type: "POST",
            url: "/main/getGroupTabList",
            success: function (json) {

                if (json) {
                    getGroupDashBoard(json);
                }
            }
        });
    }

    function getGroupDashBoard(groupList) {
        $.ajax({
            type: "POST",
            url: "/main/getGroupDashBoard",
            success: function (json) {

                $("#myTabs li:nth-child(1)").after(groupList);
                $("#home").after(json);
            }
        });
    }

    var colorRel = []; // member's color setting by click #tabList ul li -> getGroupFullCalendar

    $(document).on("click", "#tabList ul li", function () {

        var gnum = $(this).attr("id");

        getGroupFullCalendar(gnum);
        getJssorSlider(gnum);
        getGroupFile(gnum, false);
        getGroupBaordList(gnum);
        getGroupChart(gnum, false);

        window.setTimeout(function () {
            $(".fc-today-button").click();
            getGroupmaps(gnum, colorRel);
        }, 500);
    });


    Array.prototype.shuffle = function () {

        var input = this;

        for (var i = input.length - 1; i >= 0; i--) {

            var randomIndex = Math.floor(Math.random() * (i + 1));
            var itemAtIndex = input[randomIndex];

            input[randomIndex] = input[i];
            input[i] = itemAtIndex;
        }

        return input;
    };

    /* 2016-09-21 */

    function getGroupBaordList(gnum) {

        $.ajax({
            type: "POST",
            url: "/main/getGroupBaordList/" + gnum,
            success: function (json) {

                $("#group_boardList").empty();
                $("#group_boardList").append(json);

            }
        })
    }

    function getGroupFile(gnum, maOrMe) /* maOrMe is UID or master's false */ {
        console.log(maOrMe);

        if (maOrMe) {

            $.ajax({
                type: "POST",
                url: "/main/getGroupFileList/" + gnum,
                data: {
                    "gm_id": maOrMe
                },
                success: function (json) {

                    if (json) {
                        $("#group_fileList").empty();
                        $("#group_fileList").append(json);
                    }
                }
            })
        } else {

            $.ajax({
                type: "POST",
                url: "/main/getGroupFileList/" + gnum,
                success: function (json) {

                    if (json) {
                        $("#group_fileList").empty();
                        $("#group_fileList").append(json);
                    }
                }
            });
        }
    }

    function getGroupFullCalendar(gnum) {

        var colorArray = ['#02BDAA', '#FF6161', '#FF9966', '#F7D148', '#DDA9FC', '#92A501', '#BCCAF4', '#C9208E', '#0D76C6', '#A25CE8'];
        colorArray.shuffle();
        var member;
        var maxCnt;
        var count = 0;

        $("#tabDashboardCalendar" + gnum).fullCalendar({
            defaultView: "basicWeek",
            firstDay: 1,
            minTime: "06:00:00",
            scrollTime: "09:00:00", // looking sight
            maxTime: "24:00:00",
            allDaySlot: false,      // allDay off
            slotDuration: "00:30:00", // default
            slotEventOverlap: true,       // default overlad needed
            height: 300,
            events: "/main/getGroupFullEvents/" + gnum,
            eventRender: function (event, element, view) { /* 2016-08-02 color rendering is success */

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
            eventAfterAllRender: function (view) {
                if (typeof maxCnt == "undefined") {
                    maxCnt = count;
                }
            }
        });
    }

    var JssorOverlapping = []; // stop overlapping

    function getJssorSlider(gnum) {
        var jssor_options = {
            $AutoPlay: false,
            $SlideDuration: 160,
            $SlideWidth: 150,
            $SlideSpacing: 3,
            $Cols: 8,
            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$,
                $Steps: 4
            },
            $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$,
                $SpacingX: 1,
                $SpacingY: 1
            }
        };
        if (typeof (JssorOverlapping[gnum]) === 'undefined')
            JssorOverlapping[gnum] = true;
        if (JssorOverlapping[gnum]) {
            new $JssorSlider$('jssorSlider' + gnum, jssor_options);
            JssorOverlapping[gnum] = false;
        }
    }

    $(document).on("click", "div[id^=create_][id$=_events]", function () {

        var gnum = $(this).attr('id').split('_')[1];

        getGroupFile(gnum, false);
        getGroupChart(gnum, false);

        $("div[id^=member_][id$=_events]").css("border", "");
        $("div[id^=create_][id$=_events]").css("border", "");
        $(this).css("border", "3px solid #577cbd").addClass("stop");

        $("#tabDashboardCalendar" + gnum).remove();
        $("#calendarDIV_" + gnum).append("<div id='tabDashboardCalendar" + gnum + "' style='display: block; position: relative; margin-top: 150px'></div>");

        $("#tabDashboardCalendar" + gnum).fullCalendar({
            defaultView: "basicWeek",
            firstDay: 1,
            minTime: "06:00:00",
            scrollTime: "09:00:00", // looking sight
            maxTime: "24:00:00",
            allDaySlot: false,      // allDay off
            slotDuration: "00:30:00", // default
            slotEventOverlap: true,       // default overlad needed
            height: 300,
            events: "/main/getMasterFullEvents/" + gnum,
            eventRender: function (event, element, view) { /* 2016-08-02 color rendering is success */

                element.find('.fc-content').css('color', colorRel[event.gm_id]);
            }
        });
    });

    $(document).on("click", "div[id^=member_][id$=_events]", function () {

        var gnum = $(this).attr('id').split('_')[1];
        var uid = $(this).attr('id').split('_')[2];

        getGroupFile(gnum, uid);
        getGroupChart(gnum, uid);

        $("div[id^=member_][id$=_events]").css("border", "");
        $("div[id^=create_][id$=_events]").css("border", "");
        $(this).css("border", "3px solid #00c5cc");
        $(".kaitenn").removeClass("stop");

        $("#tabDashboardCalendar" + gnum).remove();
        $("#calendarDIV_" + gnum).append("<div id='tabDashboardCalendar" + gnum + "' style='display: block; position: relative; margin-top: 150px'></div>");

        $("#tabDashboardCalendar" + gnum).fullCalendar({
            defaultView: "basicWeek",
            firstDay: 1,
            minTime: "06:00:00",
            scrollTime: "09:00:00", // looking sight
            maxTime: "24:00:00",
            allDaySlot: false,      // allDay off
            slotDuration: "00:30:00", // default
            slotEventOverlap: true,       // default overlad needed
            height: 300,
            events: "/main/getMemberFullEvents/" + gnum + "/" + uid,
            eventRender: function (event, element, view) { /* 2016-08-02 color rendering is success */

                element.find('.fc-content').css('color', colorRel[event.gm_id]);
            }
        });
    });

    function getGroupmaps(gnum, colorRel) {


        var myOptions = {
            maxZoom: 16,
            zoom: 15,
            center: {lat: lat, lng: lon},
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        var maxCnt;
        var count = 0;

        $.ajax({
            url: "/group/get_member_count",
            data: {gnum: gnum},
            type: "POST",
            async: false,
            success: function (loadData) {
                loadData = JSON.parse(loadData);
                member_schedule = {};
                for (var i = 0; i < loadData.length; i++) {

                    eval("member_schedule." + loadData[i].id + "=[]");
                }
            }
        });

        groupmap = new google.maps.Map(document.getElementById("group_maps" + gnum), myOptions);
        var bounds = new google.maps.LatLngBounds();
        locations = [];
        markers = [];
        /* Navi_detail_info */
        $.ajax({
            url: "/group/loadschedule",
            data: {
                date: $.datepicker.formatDate('yy-mm-dd', new Date()),
                gnum: gnum
            },
            async: false,
            type: "POST",
            success: function (loadData) {


                schedule = JSON.parse(loadData);
                for (var i = 0; i < schedule.length; i++) {
                    for (id in member_schedule) {
                        if (id == schedule[i].id) {
                            member_schedule[id].push(schedule[i]);
                        }
                    }
                }
                for (id in member_schedule) {
                    for (var i = 0; i < member_schedule[id].length; i++) {
                        if (member_schedule[id][i].exsit_place == 1) {
                            $.ajax({
                                url: "/group/loadplace",
                                data: {
                                    SdNum: member_schedule[id][i].SdNum
                                },
                                async: false,
                                type: "POST",
                                success: function (loadData) {
                                    loadData = JSON.parse(loadData);
                                    member_schedule[id][i].place_name = loadData[0].place_name;
                                    member_schedule[id][i].latitude = loadData[0].latitude;
                                    member_schedule[id][i].longitude = loadData[0].longitude;
                                }
                            })
                        }
                    }
                }
            }
        });


        for (id in member_schedule) {
            for (var i = 0; i < member_schedule[id].length; i++) {

                if (member_schedule[id][i].exsit_place == 1) {
                    locations.push({
                        name: member_schedule[id][i].title,
                        latlng: new google.maps.LatLng(member_schedule[id][i].latitude, member_schedule[id][i].longitude),
                        user: member_schedule[id][i].name,
                        gm_id: member_schedule[id][i].gm_id
                    });        //location info
                }
            }
        }

        for (var i = 0; i < locations.length; i++) {
            var labels = i + 1;
            var marker = new google.maps.Marker({
                position: locations[i].latlng,
                map: groupmap,
                title: locations[i].name,
                label: locations[i].user
                //icon: {
                //    path: "M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z",
                //
                //    strokeColor: "#FF6161",
                //    fillColor: /*colorRel[locations[i].gm_id]*/"#FF6161"
                //}

            });
            markers.push(marker);
            bounds.extend(locations[i].latlng);
        }
        groupmap.setCenter(bounds.getCenter());
        groupmap.fitBounds(bounds);  //  all marker show on map

    }

    function getGroupChart(gnum, uid) {

        uid != false ? gm_id = uid : gm_id = false;

        // Create the chart

        importance_chart(gnum, gm_id);
        date_chart(gnum, gm_id);
    }

    function importance_chart(gnum, gm_id) {
        high = {};
        middle = {};
        row = {};

        console.log(gnum);
        $.ajax({
            url: "/group/importance_all_count",
            data: {gnum: gnum, gm_id: gm_id},
            type: "POST",
            async: false,
            success: function (data) {
                all_count = JSON.parse(data);
            }
        });
        $.ajax({
            url: "/group/importance_count",
            data: {gnum: gnum, gm_id: gm_id},
            type: "POST",
            async: false,
            success: function (data) {
                done_count = JSON.parse(data);
            }
        });

        if(all_count.length>0) {

            for (rank in all_count) {
                if (all_count[rank].firstRank == 2) {
                    high.all = all_count[rank].cnt;
                } else if (all_count[rank].firstRank == 1) {
                    middle.all = all_count[rank].cnt;
                } else if (all_count[rank].firstRank == 0) {
                    row.all = all_count[rank].cnt;
                }
            }

            for (ran in done_count) {
                if (done_count[ran].firstRank == 2) {
                    high.done = done_count[ran].cnt;
                } else if (done_count[ran].firstRank == 1) {
                    middle.done = done_count[ran].cnt;
                } else if (done_count[ran].firstRank == 0) {
                    row.done = done_count[ran].cnt;
                }
            }
        }

        if (!high.all) {
            high.all = 0;
        }
        if (!middle.all) {
            middle.all = 0;
        }
        if (!row.all) {
            row.all = 0;
        }
        if (!high.done) {
            high.done = 0;
        }
        if (!middle.done) {
            middle.done = 0;
        }
        if (!row.done) {
            row.done = 0;
        }

        high.per = high.all == 0 ? 0 : (high.done / high.all) * 100;
        middle.per = middle.all == 0 ? 0 : (middle.done / middle.all) * 100;
        row.per = row.all == 0 ? 0 : (row.done / row.all) * 100;

        $('#importance_chart').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: '重要度別達成率'
            },xAxis: {
                title: {
                    text: '重要度'
                }
            },
            yAxis: {
                title: {
                    text: 'percent (%)'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.1f}%'
                    }
                }
            },
            tooltip: {
                //headerFormat: '<span style="font-size:11px">{point.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%({point.done}/{point.all})</b><br/>'
            },
            series: [{
                name: 'Importance',
                colorByPoint: true,
                data: [{
                    name: '上',
                    y: high.per,
                    done:high.done,
                    all:high.all,
                    drilldown: null
                }, {
                    name: '中',
                    y: middle.per,
                    done:middle.done,
                    all:middle.all,
                    drilldown: null
                }, {
                    name: '下',
                    y: row.per,
                    done:row.done,
                    all:row.all,
                    drilldown: null
                }]
            }]
        });
    }

    function date_chart(gnum, gm_id) {

        $.ajax({
            url: "/group/date_all_count",
            data: {gnum: gnum, gm_id: gm_id},
            type: "POST",
            async: false,
            success: function (data) {
                all_count = JSON.parse(data);
            }
        });
        $.ajax({
            url: "/group/date_count",
            data: {gnum: gnum, gm_id: gm_id},
            type: "POST",
            async: false,
            success: function (data) {
                done_count = JSON.parse(data);
            }
        });

        var date = [];
        if(all_count.length!=0) {
            for (var i = 0; i < all_count.length; i++) {
                date[i] = {};
                date[i].all = all_count[i].cnt;
                date[i].day = all_count[i].day;
            }
            for (var i = 0; i < date.length; i++) {
                for (da in done_count) {
                    if (done_count[da].day == date[i].day) {
                        date[i].done = done_count[da].cnt;
                    }
                }
            }
            for (var i = 0; i < date.length; i++) {
                if (!date[i].all) {
                    date[i].all = 0;
                }
                if (!date[i].done) {
                    date[i].done = 0;
                }
                date[i].per = date[i].all == 0 ? 0 : (date[i].done / date[i].all) * 100;
            }
        }

        var all_day = [];
        for (var i = 0; i < date.length; i++) {
            all_day[i] = date[i].day;
        }
        var all_per = [];
        var all_all = [];
        var all_done = [];
        for (var i = 0; i < date.length; i++) {
            all_per[i] = Number((date[i].per).toFixed(2));
            all_all[i] = date[i].all;
            all_done[i]= date[i].done;
        }
        //console.log(all_per);

        $('#date_chart').highcharts({
            chart: {
                type: 'line'
            },
            title: {
                text: '日付別達成率'
            },
            xAxis: {
                categories: all_day
            },
            yAxis: {
                title: {
                    text: 'percent (%)'
                }
            },
            tooltip:{
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.done}/{point.all}</b><br/>'
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true
                }
            },
            series: [{
                name: '全体',
                data: all_per,
                done:all_done,
                all:all_all
            }]
        });
    }
});
