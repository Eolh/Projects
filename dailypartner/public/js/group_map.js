var mymap;
var geocoder;
var schedule;
var today = new Date();

var directionsService;
var directionsDisplay;
var scheduleCheck = 0;

$(document).ready(function () {
    current_date();
    get_members();
});
window.onload = geolocateUser;

function get_members() {
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
    })
}
$(document).on('click', '.group_member', function () {

    if($(this).attr('latitude')) {
        var mlat = $(this).attr('latitude');
        var mlong = $(this).attr('longitude');
        var scheduleSet = new google.maps.LatLng(mlat, mlong);
        mymap.setZoom(17);
        console.log(scheduleSet);
        mymap.panTo(scheduleSet);
    }

});
/* #navi title : .date */
function current_date() {

    curdate = $.datepicker.formatDate('yy-mm-dd', today);

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
        curdate.setDate(curdate.getDate() + 1);
    } else {
        curdate.setDate(curdate.getDate() - 1);
    }
    curdate = $.datepicker.formatDate('yy-mm-dd', curdate);

    $('#navi_title').html(curdate);

    //directionsDisplay = new google.maps.DirectionsRenderer();

    var myOptions = {
        maxZoom: 17,
        zoom: 10,
        center: userLatLng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true
    };

    if (markers) {
        while (markers.length) {
            markers.pop().setMap(null);
        }
        markers.length = 0;
        directionsDisplays.length = 0;
        directionsServices.length = 0;
        locations.length = 0;
    }

    /* 현 위치 marker */
    new google.maps.Marker({

        map: mymap,
        position: userLatLng,
        icon: "/public/img/marker/32-32-pin-o.png"

    });

    var bounds = new google.maps.LatLngBounds(); //  all marker show on map
    bounds.extend(userLatLng);

    locations = [];

    directionsServices = {};
    directionsDisplays = {};

    markers = [];
    /* Navi_detail_info */
    $.ajax({
        url: "/group/loadschedule",
        data: {
            date: curdate,
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
                                console.log(member_schedule);
                                return false;
                            }
                        })
                    }
                }


                console.log(member_schedule);
                var cnt = member_schedule[id].length;
                eval("directionsServices." + id + "=[]");
                eval("directionsDisplays." + id + "=[]");


                for (var i = 0; i < cnt; i++) {
                    directionsServices[id][i] = new google.maps.DirectionsService();

                    var stime = member_schedule[id][i].startTime;
                    var ltime = member_schedule[id][i].lastTime;
                    var title = member_schedule[id][i].title;
                    var content = member_schedule[id][i].content;

                    if (member_schedule[id][i].exsit_place == 1) {
                        var pname = member_schedule[id][i].place_name;
                        var latitude = Number(member_schedule[id][i].latitude);
                        var longitude = Number(member_schedule[id][i].longitude);
                    }

                    st = stime.replace(/:/g, "");
                    lt = ltime.replace(/:/g, "");

                    /* 예정 데이터 */
                    if (st < current_time && lt > current_time) {
                        $('#' + id).attr('latitude', member_schedule[id][i].latitude);
                        $('#' + id).attr('longitude', member_schedule[id][i].longitude);
                    }

                    if (member_schedule[id][i].exsit_place == 1) {

                        if (i > 0) {
                            var start = new google.maps.LatLng({
                                lat: Number(member_schedule[id][i - 1].latitude),
                                lng: Number(member_schedule[id][i - 1].longitude)
                            });
                            var end = new google.maps.LatLng(latitude, longitude);

                            var request = {
                                origin: start,
                                destination: end,
                                optimizeWaypoints: true,
                                travelMode: google.maps.TravelMode.TRANSIT,
                                unitSystem: google.maps.UnitSystem.METRIC
                            };


                            directionsServices[id][i].route(request, function (response, status) {
                                if (status == google.maps.DirectionsStatus.OK) {
                                    directionsDisplays[id].push(new google.maps.DirectionsRenderer({
                                        preserveViewpost: true,
                                        suppressMarkers: true
                                    }));

                                    directionsDisplays[id][directionsDisplays[id].length - 1].setMap(mymap);
                                    directionsDisplays[id][directionsDisplays[id].length - 1].setDirections(response);
                                }
                            });
                        }
                    }
                    //}

                }
            }
        }
    });


    for (var i = 0; i < schedule.length; i++) {
        if (schedule[i].exsit_place == 1) {
            locations.push({
                name: schedule[i].title,
                latlng: new google.maps.LatLng(schedule[i].latitude, schedule[i].longitude),
                user: schedule[i].name
            });        //location info
        }
    }


    for (var i = 0; i < locations.length; i++) {
        var labels = i + 1;
        var marker = new google.maps.Marker({
            position: locations[i].latlng,
            map: mymap,
            title: locations[i].name,
            label: "" + locations[i].user
        });
        markers.push(marker);
        bounds.extend(locations[i].latlng);
        bindInfoWindow(marker, mymap, infowindow, schedule[i]);
    }

    var check = true;

    function bindInfoWindow(marker, map, infowindow, data) {
        marker.addListener('click', function () {
            if (check) {
                infowindow.setContent("<h3>" + data.title + "</h3><p>" + data.startTime + "~" + data.lastTime + "</p><p>" + data.content + "</p>");
                infowindow.open(map, this);
                check = false;
            } else {
                infowindow.close(map, this);
                check = true;

            }
        });

    }

    //mymap.setCenter(bounds.getCenter());
    //mymap.fitBounds(bounds);  //  all marker show on map

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

    var groupMembers = (document.getElementById("group_members"));
    mymap.controls[google.maps.ControlPosition.TOP_RIGHT].push(groupMembers);

    var leftside = (document.getElementById("leftSide"));
    mymap.controls[google.maps.ControlPosition.TOP_LEFT].push(leftside);

    /* 현 위치 marker */
    new google.maps.Marker({

        map: mymap,
        position: userLatLng,
        icon: "/public/img/marker/32-32-pin-o.png"

    });

    var bounds = new google.maps.LatLngBounds(); //  all marker show on map
    //bounds.extend(userLatLng);

    directionsServices = {};
    directionsDisplays = {};
    locations = [];
    markers = [];
    /* Navi_detail_info */
    $.ajax({
        url: "/group/loadschedule",
        data: {
            date: curdate,
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
                                console.log(member_schedule);
                                return false;
                            }
                        })
                    }
                }


                var cnt = member_schedule[id].length;
                eval("directionsServices." + id + "=[]");
                eval("directionsDisplays." + id + "=[]");


                for (var i = 0; i < cnt; i++) {
                    directionsServices[id][i] = new google.maps.DirectionsService();

                    var stime = member_schedule[id][i].startTime;
                    var ltime = member_schedule[id][i].lastTime;
                    var title = member_schedule[id][i].title;
                    var content = member_schedule[id][i].content;

                    if (member_schedule[id][i].exsit_place == 1) {
                        var pname = member_schedule[id][i].place_name;
                        var latitude = Number(member_schedule[id][i].latitude);
                        var longitude = Number(member_schedule[id][i].longitude);
                    }

                    st = stime.replace(/:/g, "");
                    lt = ltime.replace(/:/g, "");
                    //console.log(id);
                    var selid = "#" + id;
                    //console.log(id);
                    if (st < current_time && lt > current_time) {
                        $(selid).attr('latitude', member_schedule[id][i].latitude);
                        $(selid).attr('longitude', member_schedule[id][i].longitude);

                    }

                    if (member_schedule[id][i].exsit_place == 1) {

                        if (i > 0) {
                            var start = new google.maps.LatLng({
                                lat: Number(member_schedule[id][i - 1].latitude),
                                lng: Number(member_schedule[id][i - 1].longitude)
                            });
                            var end = new google.maps.LatLng(latitude, longitude);

                            var request = {
                                origin: start,
                                destination: end,
                                optimizeWaypoints: true,
                                travelMode: google.maps.TravelMode.TRANSIT,
                                unitSystem: google.maps.UnitSystem.METRIC
                            };


                            directionsServices[id][i].route(request, function (response, status) {
                                if (status == google.maps.DirectionsStatus.OK) {
                                    directionsDisplays[id].push(new google.maps.DirectionsRenderer({
                                        preserveViewpost: true,
                                        suppressMarkers: true
                                    }));

                                    directionsDisplays[id][directionsDisplays[id].length - 1].setMap(mymap);
                                    directionsDisplays[id][directionsDisplays[id].length - 1].setDirections(response);
                                }
                            });
                        }
                    }


                }
            }
        }
    });


    for (var i = 0; i < schedule.length; i++) {
        if (schedule[i].exsit_place == 1) {
            locations.push({
                name: schedule[i].title,
                latlng: new google.maps.LatLng(schedule[i].latitude, schedule[i].longitude),
                user: schedule[i].name
            });        //location info
        }
    }


    for (var i = 0; i < locations.length; i++) {
        var labels = i + 1;
        var marker = new google.maps.Marker({
            position: locations[i].latlng,
            map: mymap,
            title: locations[i].name,
            label: "" + locations[i].user
        });
        markers.push(marker);
        //bounds.extend(locations[i].latlng);
        bindInfoWindow(marker, mymap, infowindow, schedule[i]);
    }

    var check = true;

    function bindInfoWindow(marker, map, infowindow, data) {
        marker.addListener('click', function () {
            if (check) {
                infowindow.setContent("<h3>" + data.title + "</h3><p>" + data.startTime + "~" + data.lastTime + "</p><p>" + data.content + "</p>");
                infowindow.open(map, this);
                check = false;
            } else {
                infowindow.close(map, this);
                check = true;

            }
        });

    }

    //mymap.setCenter(bounds.getCenter());
    //mymap.fitBounds(bounds);  //  all marker show on map

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

    for (var i = 0; i < locations.length; i++) {

        if (locations[i].latlng.latitude == scheduleSet.latitude && locations[i].latlng.latitude == scheduleSet.longitude) {

            mymap.panTo(scheduleSet);
            mymap.setzoom(17);
        }
    }
});

$(function () {
    $('#datepicker').datepicker({
        buttionImage: "/public/img/dateicon.png",
        showOn: 'both',
        buttonImageOnly: true,
        dateFormat: 'yy-mm-dd'
    });
    $('#datepicker').change(function () {
        curdate = $('#datepicker').val();
        $('#navi_title').html(curdate);
        infowindow = new google.maps.InfoWindow({content: ""});
        var myOptions = {
            maxZoom: 17,
            zoom: 10,
            center: userLatLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true
        };

        if (directionsDisplays[0]) {
            directionsDisplays.pop().setMap(null);
        }
        if (markers) {

            while (markers.length) {
                markers.pop().setMap(null);
            }

            markers.length = 0;
            directionsDisplays.length = 0;
            directionsServices.length = 0;
            locations.length = 0;
        }

        /* 현 위치 marker */
        new google.maps.Marker({

            map: mymap,
            position: userLatLng,
            icon: "/public/img/marker/32-32-pin-o.png"

        });

        var bounds = new google.maps.LatLngBounds(); //  all marker show on map
        bounds.extend(userLatLng);

        locations = [];

        directionsServices = {};
        directionsDisplays = {};
        locations = [];
        markers = [];
        /* Navi_detail_info */
        $.ajax({
            url: "/group/loadschedule",
            data: {
                date: curdate,
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
                                    console.log(member_schedule);
                                    return false;
                                }
                            })
                        }
                    }


                    console.log(member_schedule);
                    var cnt = member_schedule[id].length;
                    eval("directionsServices." + id + "=[]");
                    eval("directionsDisplays." + id + "=[]");


                    for (var i = 0; i < cnt; i++) {
                        directionsServices[id][i] = new google.maps.DirectionsService();

                        var stime = member_schedule[id][i].startTime;
                        var ltime = member_schedule[id][i].lastTime;
                        var title = member_schedule[id][i].title;
                        var content = member_schedule[id][i].content;

                        if (member_schedule[id][i].exsit_place == 1) {
                            var pname = member_schedule[id][i].place_name;
                            var latitude = Number(member_schedule[id][i].latitude);
                            var longitude = Number(member_schedule[id][i].longitude);
                        }

                        st = stime.replace(/:/g, "");
                        lt = ltime.replace(/:/g, "");


                        if (st < current_time && lt > current_time) {
                            $('#' + id).attr('latitude', member_schedule[id][i].latitude);
                            $('#' + id).attr('longitude', member_schedule[id][i].longitude);
                        }

                        if (member_schedule[id][i].exsit_place == 1) {

                            if (i > 0) {
                                var start = new google.maps.LatLng({
                                    lat: Number(member_schedule[id][i - 1].latitude),
                                    lng: Number(member_schedule[id][i - 1].longitude)
                                });
                                var end = new google.maps.LatLng(latitude, longitude);

                                var request = {
                                    origin: start,
                                    destination: end,
                                    optimizeWaypoints: true,
                                    travelMode: google.maps.TravelMode.TRANSIT,
                                    unitSystem: google.maps.UnitSystem.METRIC
                                };


                                directionsServices[id][i].route(request, function (response, status) {
                                    if (status == google.maps.DirectionsStatus.OK) {
                                        directionsDisplays[id].push(new google.maps.DirectionsRenderer({
                                            preserveViewpost: true,
                                            suppressMarkers: true
                                        }));

                                        directionsDisplays[id][directionsDisplays[id].length - 1].setMap(mymap);
                                        directionsDisplays[id][directionsDisplays[id].length - 1].setDirections(response);
                                    }
                                });
                            }
                        }


                    }
                }
            }
        });


        for (var i = 0; i < schedule.length; i++) {
            if (schedule[i].exsit_place == 1) {
                locations.push({
                    name: schedule[i].title,
                    latlng: new google.maps.LatLng(schedule[i].latitude, schedule[i].longitude),
                    user: schedule[i].name
                });        //location info
            }
        }


        for (var i = 0; i < locations.length; i++) {
            var labels = i + 1;
            var marker = new google.maps.Marker({
                position: locations[i].latlng,
                map: mymap,
                title: locations[i].name,
                label: "" + locations[i].user
            });
            markers.push(marker);
            bounds.extend(locations[i].latlng);
            bindInfoWindow(marker, mymap, infowindow, schedule[i]);
        }

        var check = true;

        function bindInfoWindow(marker, map, infowindow, data) {
            marker.addListener('click', function () {
                if (check) {
                    infowindow.setContent("<h3>" + data.title + "</h3><p>" + data.startTime + "~" + data.lastTime + "</p><p>" + data.content + "</p>");
                    infowindow.open(map, this);
                    check = false;
                } else {
                    infowindow.close(map, this);
                    check = true;

                }
            });

        }

        //mymap.setCenter(bounds.getCenter());
        //mymap.fitBounds(bounds);  //  all marker show on map

    });
});