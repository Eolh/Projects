var mymap;
var geocoder;
var schedule;
var today = new Date();

var directionsService;
var directionsDisplay;
var scheduleCheck = 0;

$(document).ready(function () {
    current_date();

});
window.onload = geolocateUser;

/* #navi title : .date */
function current_date() {

    curdate = $.datepicker.formatDate('yy-mm-dd',today);

    hour = today.getHours();   //현재 시간 중 시간.
    if ((hour + "").length < 2) {
        hour = "0" + hour;
    }
    min = today.getMinutes();
    if ((min + "").length < 2) {
        min = "0" + min;
    }
    sec = today.getSeconds();
    if ((sec + "").length < 2) {
        sec = "0" + sec;
    }

    $('#navi_title').html(curdate);

    current_time = hour + "" + min + "" + sec;
}

$(document).on('click', '.datemove', function () {
    var id = $(this).attr('id');

    curdate = new Date(curdate);

    if (id == 'nextday') {
        curdate.setDate(curdate.getDate()+1);
    } else {
        curdate.setDate(curdate.getDate()-1);
    }
    curdate = $.datepicker.formatDate('yy-mm-dd',curdate);

    $('#navi_title').html(curdate);

    //directionsDisplay = new google.maps.DirectionsRenderer();

    infowindow = new google.maps.InfoWindow({content: ""});
    var myOptions = {
        maxZoom: 17,
        zoom: 10,
        center: userLatLng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true
    };

    if(directionsDisplays[0]){
        directionsDisplays.pop().setMap(null);
    }
    if (markers) {
        for (i = 0; i < markers.length; i++) {
            markers.pop().setMap(null);
        }

        markers.length = 0;
        directionsDisplays.length = 0;
        directionsServices.length = 0;
        locations.length = 0;
    }

    /* 현 위치 marker */
    new google.maps.Marker({
        zoom: 7,
        map: mymap,
        position: userLatLng,
        icon: "/public/img/marker/32-32-pin-o.png"

    });

    var bounds = new google.maps.LatLngBounds(); //  all marker show on map
    bounds.extend(userLatLng);

    locations = [];

    $(".Itemlist").remove();

    /* Navi_detail_info */
    $.ajax({

        url: "/maps/loadschedule",
        data: {
            date: curdate
        },
        async: false,
        type: "POST",
        success: function (loadData) {
            schedule = JSON.parse(loadData);

            var cnt = schedule.length;


            directionsServices = [];
            directionsDisplays = [];
            for (var i = 0; i < cnt; i++) {

                directionsServices[i] = new google.maps.DirectionsService();

                var stime = schedule[i].startTime;
                var ltime = schedule[i].lastTime;
                var title = schedule[i].title;
                var content = schedule[i].content;
                var pname = schedule[i].place_name;

                var latitude = Number(schedule[i].latitude);
                var longitude = Number(schedule[i].longitude);

                var item = $("<div class='Itemlist btn btn-raised'></div>").attr("data-id", i).attr('id', "sche" + i);
                //data-id="0" id="sche0"

                st = stime.replace(/:/g, "");
                lt = ltime.replace(/:/g, "");
                /* 과거 데이터 */
                if (lt < current_time) {
                    item.addClass("past_data");
                    console.log(st + "b" + lt + " " + current_time);
                }
                /* 진행중 */
                else if (st < current_time && current_time < lt) {
                    item.addClass("current_data");
                    console.log(st + "a" + lt + " " + current_time);
                }
                /* 예정 데이터 */
                else {
                    item.addClass("future_data");
                    console.log(st + "c" + lt + " " + current_time);
                    scheduleCheck++;

                    var start = (scheduleCheck == 1) ? userLatLng : new google.maps.LatLng({
                        lat: Number(schedule[i - 1].latitude),
                        lng: Number(schedule[i - 1].longitude)
                    });
                    var end = new google.maps.LatLng(latitude, longitude);

                    var request = {
                        origin: start,
                        destination: end,
                        optimizeWaypoints: true,
                        travelMode: google.maps.TravelMode.TRANSIT,
                        unitSystem: google.maps.UnitSystem.METRIC
                    };


                    directionsServices[i].route(request, function (response, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            directionsDisplays.push(new google.maps.DirectionsRenderer({
                                preserveViewpost: true,
                                suppressMarkers: true
                            }));

                            directionsDisplays[directionsDisplays.length - 1].setMap(mymap);
                            directionsDisplays[directionsDisplays.length - 1].setDirections(response);
                        } else {
                            window.alert(response + " , " + status);
                        }

                    });
                }


                /* Itemlist contents : itemtext(.Time) */
                $("<p class='Time'></p>").text(stime + " ~ " + ltime).appendTo(item);
                $("<p class='place glyphicon'></p>").html("<span style='word-break: break-all;'>" + pname + "</span>").appendTo(item);
                $("<p class='title'></p>").text(title).appendTo(item);
                $("<input>").attr({
                    type: 'hidden',
                    id: 'latitude',
                    value: latitude
                }).appendTo(item);
                $("<input>").attr({
                    type: 'hidden',
                    id: 'longitude',
                    value: longitude
                }).appendTo(item);

                item.appendTo($("#leftSide"));

            }

        }
    });

    for (var i = 0; i < schedule.length; i++) {
        locations.push({
            name: schedule[i].title,
            latlng: new google.maps.LatLng(schedule[i].latitude, schedule[i].longitude)

        });        //location info
    }

    for (var i = 0; i < locations.length; i++) {
        var labels = i + 1;
        var marker = new google.maps.Marker({
            position: locations[i].latlng,
            map: mymap,
            title: locations[i].name,
            label: "" + labels
        });
        markers.push(marker);
        bounds.extend(locations[i].latlng);
        bindInfoWindow(marker, mymap, infowindow, schedule[i]);
    }

    var check = true;

    function bindInfoWindow(marker, map, infowindow, data) {
        marker.addListener('click', function () {
            if (check) {
                infowindow.setContent("<h5>" + data.title + "</h5><p>" + data.startTime + "~" + data.lastTime + "</p><p>" + data.content + "</p>");
                infowindow.open(map, this);
                check = false;
            } else {
                infowindow.close(map, this);
                check = true;

            }
        });

    }

    mymap.setCenter(bounds.getCenter());
    mymap.fitBounds(bounds);  //  all marker show on map


});


function geolocationSuccess(position) {

    userLatLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
    directionsDisplay = new google.maps.DirectionsRenderer();

    infowindow = new google.maps.InfoWindow({content: ""});
    var myOptions = {
        maxZoom: 17,
        zoom: 10,
        center: userLatLng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true
    };

    mymap = new google.maps.Map(document.getElementById("mymap"), myOptions);

    var leftside = (document.getElementById("leftSide"));
    mymap.controls[google.maps.ControlPosition.TOP_LEFT].push(leftside);

    /* 현 위치 marker */
    new google.maps.Marker({
        zoom: 7,
        map: mymap,
        position: userLatLng,
        icon: "/public/img/marker/32-32-pin-o.png"

    });

    var bounds = new google.maps.LatLngBounds(); //  all marker show on map
    bounds.extend(userLatLng);

    locations = [];
    markers= [];
    /* Navi_detail_info */
    $.ajax({
        url: "/maps/loadschedule",
        data: {
            date: curdate
        },
        async: false,
        type: "POST",
        success: function (loadData) {
            schedule = JSON.parse(loadData);
            var cnt = schedule.length;
            directionsServices = [];
            directionsDisplays = [];

            for (var i = 0; i < cnt; i++) {
                directionsServices[i] = new google.maps.DirectionsService();

                var stime = schedule[i].startTime;
                var ltime = schedule[i].lastTime;
                var title = schedule[i].title;
                var content = schedule[i].content;
                var pname = schedule[i].place_name;

                var latitude = Number(schedule[i].latitude);
                var longitude = Number(schedule[i].longitude);

                var item = $("<div class='Itemlist btn btn-raised'></div>").attr("data-id", i).attr('id', "sche" + i);
                //data-id="0" id="sche0"

                st = stime.replace(/:/g, "");
                lt = ltime.replace(/:/g, "");
                /* 과거 데이터 */
                if (lt < current_time) {
                    item.addClass("past_data");
                    console.log(st + "b" + lt + " " + current_time);
                }
                /* 진행중 */
                else if (st < current_time && current_time < lt) {
                    item.addClass("current_data");
                    console.log(st + "a" + lt + " " + current_time);
                }
                /* 예정 데이터 */
                else {
                    item.addClass("future_data");
                    console.log(st + "c" + lt + " " + current_time);
                    scheduleCheck++;

                    var start = (scheduleCheck == 1) ? userLatLng : new google.maps.LatLng({
                        lat: Number(schedule[i - 1].latitude),
                        lng: Number(schedule[i - 1].longitude)
                    });
                    var end = new google.maps.LatLng(latitude, longitude);

                    var request = {
                        origin: start,
                        destination: end,
                        optimizeWaypoints: true,
                        travelMode: google.maps.TravelMode.TRANSIT,
                        unitSystem: google.maps.UnitSystem.METRIC
                    };


                    directionsServices[i].route(request, function (response, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            directionsDisplays.push(new google.maps.DirectionsRenderer({
                                preserveViewpost: true,
                                suppressMarkers: true
                            }));

                            directionsDisplays[directionsDisplays.length - 1].setMap(mymap);
                            directionsDisplays[directionsDisplays.length - 1].setDirections(response);
                        } else {
                            window.alert(response + " , " + status);
                        }

                    });
                }

                /* Itemlist contents : itemtext(.Time) */
                $("<p class='Time'></p>").text(stime + " ~ " + ltime).appendTo(item);
                $("<p class='place glyphicon'></p>").html("<span style='word-break: break-all;'>" + pname + "</span>").appendTo(item);
                $("<p class='title'></p>").text(title).appendTo(item);
                $("<input>").attr({
                    type: 'hidden',
                    id: 'latitude',
                    value: latitude
                }).appendTo(item);
                $("<input>").attr({
                    type: 'hidden',
                    id: 'longitude',
                    value: longitude
                }).appendTo(item);
                item.appendTo($("#leftSide"));

            }

        }
    });

    for (var i = 0; i < schedule.length; i++) {
        locations.push({
            name: schedule[i].title,
            latlng: new google.maps.LatLng(schedule[i].latitude, schedule[i].longitude)

        });        //location info
    }


    for (var i = 0; i < locations.length; i++) {
        var labels = i + 1;
        var marker = new google.maps.Marker({
            position: locations[i].latlng,
            map: mymap,
            title: locations[i].name,
            label: "" + labels
        });
        markers.push(marker);
        bounds.extend(locations[i].latlng);
        bindInfoWindow(marker, mymap, infowindow, schedule[i]);
    }

    var check = true;

    function bindInfoWindow(marker, map, infowindow, data) {
        marker.addListener('click', function () {
            if (check) {
                infowindow.setContent("<h5>" + data.title + "</h5><p>" + data.startTime + "~" + data.lastTime + "</p><p>" + data.content + "</p>");
                infowindow.open(map, this);
                check = false;
            } else {
                infowindow.close(map, this);
                check = true;

            }
        });

    }

    mymap.setCenter(bounds.getCenter());
    mymap.fitBounds(bounds);  //  all marker show on map

}


function geolocationError(positionError) {
}

function geolocateUser() {
    if (navigator.geolocation) {

        var positionOptions = {
            enableHighAccuracy: true,
            timeout: 10 * 1000 // 10seconds
        };

        navigator.geolocation.getCurrentPosition(geolocationSuccess, geolocationError, positionOptions)
    } else {
        console.log("not locations");
    }
}


$(document).on('click', '.Itemlist', function () {
    var mlat = $(this).children('#latitude').val();
    var mlong = $(this).children('#longitude').val();
    var scheduleSet = new google.maps.LatLng(mlat, mlong);
    mymap.setZoom(15);
    for (var i = 0; i < locations.length; i++) {

        if (locations[i].latlng.latitude == scheduleSet.latitude && locations[i].latlng.latitude == scheduleSet.longitude) {

            mymap.panTo(scheduleSet);
        }
    }
});

$(function(){
    $('#datepicker').datepicker({
        buttionImage:"/public/img/dateicon.png",
        showOn:'both',
        buttonImageOnly:true,
        dateFormat:'yy-mm-dd'
    });
    $('#datepicker').change(function(){
        curdate=$('#datepicker').val();
        $('#navi_title').html(curdate);
        infowindow = new google.maps.InfoWindow({content: ""});
        var myOptions = {
            maxZoom: 17,
            zoom: 10,
            center: userLatLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true
        };

        if(directionsDisplays[0]){
            directionsDisplays.pop().setMap(null);
        }
        if (markers) {
            for (i = 0; i < markers.length; i++) {
                markers.pop().setMap(null);
            }

            markers.length = 0;
            directionsDisplays.length = 0;
            directionsServices.length = 0;
            locations.length = 0;
        }

        /* 현 위치 marker */
        new google.maps.Marker({
            zoom: 7,
            map: mymap,
            position: userLatLng,
            icon: "/public/img/marker/32-32-pin-o.png"

        });

        var bounds = new google.maps.LatLngBounds(); //  all marker show on map
        bounds.extend(userLatLng);

        locations = [];

        $(".Itemlist").remove();

        /* Navi_detail_info */
        $.ajax({

            url: "/maps/loadschedule",
            data: {
                date: curdate
            },
            async: false,
            type: "POST",
            success: function (loadData) {
                schedule = JSON.parse(loadData);

                var cnt = schedule.length;


                directionsServices = [];
                directionsDisplays = [];
                for (var i = 0; i < cnt; i++) {

                    directionsServices[i] = new google.maps.DirectionsService();

                    var stime = schedule[i].startTime;
                    var ltime = schedule[i].lastTime;
                    var title = schedule[i].title;
                    var content = schedule[i].content;
                    var pname = schedule[i].place_name;

                    var latitude = Number(schedule[i].latitude);
                    var longitude = Number(schedule[i].longitude);

                    var item = $("<div class='Itemlist btn btn-raised'></div>").attr("data-id", i).attr('id', "sche" + i);
                    //data-id="0" id="sche0"

                    st = stime.replace(/:/g, "");
                    lt = ltime.replace(/:/g, "");
                    /* 과거 데이터 */
                    if (lt < current_time) {
                        item.addClass("past_data");
                        console.log(st + "b" + lt + " " + current_time);
                    }
                    /* 진행중 */
                    else if (st < current_time && current_time < lt) {
                        item.addClass("current_data");
                        console.log(st + "a" + lt + " " + current_time);
                    }
                    /* 예정 데이터 */
                    else {
                        item.addClass("future_data");
                        console.log(st + "c" + lt + " " + current_time);
                        scheduleCheck++;

                        var start = (scheduleCheck == 1) ? userLatLng : new google.maps.LatLng({
                            lat: Number(schedule[i - 1].latitude),
                            lng: Number(schedule[i - 1].longitude)
                        });
                        var end = new google.maps.LatLng(latitude, longitude);

                        var request = {
                            origin: start,
                            destination: end,
                            optimizeWaypoints: true,
                            travelMode: google.maps.TravelMode.TRANSIT,
                            unitSystem: google.maps.UnitSystem.METRIC
                        };


                        directionsServices[i].route(request, function (response, status) {
                            if (status == google.maps.DirectionsStatus.OK) {
                                directionsDisplays.push(new google.maps.DirectionsRenderer({
                                    preserveViewpost: true,
                                    suppressMarkers: true
                                }));

                                directionsDisplays[directionsDisplays.length - 1].setMap(mymap);
                                directionsDisplays[directionsDisplays.length - 1].setDirections(response);
                            } else {
                                window.alert(response + " , " + status);
                            }

                        });
                    }


                    /* Itemlist contents : itemtext(.Time) */
                    $("<p class='Time'></p>").text(stime + " ~ " + ltime).appendTo(item);
                    $("<p class='place glyphicon'></p>").html("<span style='word-break: break-all;'>" + pname + "</span>").appendTo(item);
                    $("<p class='title'></p>").text(title).appendTo(item);
                    $("<input>").attr({
                        type: 'hidden',
                        id: 'latitude',
                        value: latitude
                    }).appendTo(item);
                    $("<input>").attr({
                        type: 'hidden',
                        id: 'longitude',
                        value: longitude
                    }).appendTo(item);

                    item.appendTo($("#leftSide"));

                }

            }
        });

        for (var i = 0; i < schedule.length; i++) {
            locations.push({
                name: schedule[i].title,
                latlng: new google.maps.LatLng(schedule[i].latitude, schedule[i].longitude)

            });        //location info
        }

        for (var i = 0; i < locations.length; i++) {
            var labels = i + 1;
            var marker = new google.maps.Marker({
                position: locations[i].latlng,
                map: mymap,
                title: locations[i].name,
                label: "" + labels
            });
            markers.push(marker);
            bounds.extend(locations[i].latlng);
            bindInfoWindow(marker, mymap, infowindow, schedule[i]);
        }

        var check = true;

        function bindInfoWindow(marker, map, infowindow, data) {
            marker.addListener('click', function () {
                if (check) {
                    infowindow.setContent("<h5>" + data.title + "</h5><p>" + data.startTime + "~" + data.lastTime + "</p><p>" + data.content + "</p>");
                    infowindow.open(map, this);
                    check = false;
                } else {
                    infowindow.close(map, this);
                    check = true;

                }
            });

        }

        mymap.setCenter(bounds.getCenter());
        mymap.fitBounds(bounds);  //  all marker show on map

    });
});