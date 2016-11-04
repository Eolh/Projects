<!DOCTYPE html>
<html>
<head>
    <!-- start: PAGE SETTINGS -->
    <title>balancemyschedule</title>
    <!-- start:   META -->
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
    <!--<link rel="stylesheet" href="/public/css/animate.css">-->

    <!-- Bootstrap Material Design -->
    <link rel="stylesheet" type="text/css" href="/public/export_modules/dist/css/bootstrap-material-design.css">
    <link rel="stylesheet" type="text/css" href="/public/export_modules/dist/css/ripples.min.css">

    <!-- Todolist & Command -->
    <link rel="stylesheet" href="/public/export_modules/jquery-ui/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="/public/css/bookblock.css"/>
    <link rel="stylesheet" type="text/css" href="/public/css/todoList.css"/>

    <link rel="stylesheet" href="/public/css/main_header.css">

    <!-- This page -->
    <link rel="stylesheet" href="/public/css/maps.css">
    <!-- end:   CSS -->

    <!--================================== start: script =================================-->
    <script src="/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="/public/export_modules/jquery-ui/jquery-ui.js"></script>
    <script src="/public/export_modules/bootstrap/js/bootstrap.min.js"></script>

    <!-- Todolist & Command -->
    <script src="/public/js/modernizr.custom.js"></script>
    <script src="/public/js/jquerypp.custom.js"></script>
    <script src="/public/js/jquery.bookblock.js"></script>

    <!-- Bootstrap Material Js -->
    <script src="/public/export_modules/dist/js/material.min.js"></script>
    <script src="/public/export_modules/dist/js/ripples.min.js"></script>

    <!-- This Page -->
    <script src="/public/js/weather.js"></script>
    <script src="/public/js/dashBoard.js"></script>
    <script type="text/javascript"
            src="https://maps.google.com/maps/api/js?key=AIzaSyBrJB3StK72knzhX4-9IpdTH422LdhAndM&v3.23&region=KR&libraries=places"
            async defer></script>

    <script src="/public/export_modules/fullcalendar/lib/moment.min.js"></script>
    <script src="/public/export_modules/fullcalendar/fullcalendar.js"></script>
    <script src="/public/export_modules/fullcalendar/lang/ko.js"></script>

    <!--material동작처리-->
    <script src="/public/js/command.js"></script>
    <!-- end:   script -->

    <script type="text/javascript"
            src="https://maps.google.com/maps/api/js?key=AIzaSyBrJB3StK72knzhX4-9IpdTH422LdhAndM&v=3.23&region=KR&libraries=places"
            async defer></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>


    <script>
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
        function current_date() {
            var day = today.getDate();
            var month = today.getMonth() + 1;
            var year = today.getFullYear();
            var current_day = year + "-" + month + "-" + day;
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
            $('<div class="date"></div>').html("<h4>" + current_day + "</h4>").appendTo($('#leftSide'));
            current_time = hour + "" + min + "" + sec;
        }


        function geolocationSuccess(position) {

            userLatLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            directionsDisplay = new google.maps.DirectionsRenderer();

            infowindow = new google.maps.InfoWindow({content: ""});
            var myOptions = {
                maxZoom: 16,
                zoom: 10,
                center: userLatLng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true
            };

            mymap = new google.maps.Map(document.getElementById("mymap"), myOptions);

            var leftside = (document.getElementById('leftSide'));
            mymap.controls[google.maps.ControlPosition.TOP_LEFT].push(leftside);

            new google.maps.Marker({
                zoom: 7,
                map: mymap,
                position: userLatLng,
                icon: 'https://maps.google.com/mapfiles/kml/pal4/icon49.png'

            });


            var bounds = new google.maps.LatLngBounds(); //  all marker show on map
            bounds.extend(userLatLng);


            locations = [];

            $.ajax({
                url: "/maps/loadschedule",
                data: {UID: <?=$_SESSION['login']['UID'];?>},
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

                        var item = $('<div></div>').attr('data-id', i).addClass('Item').attr('id', "sche" + i);

                        var itemtext = $('<div class="Time"></div>').html('<div>' + stime + '~' + ltime + '</div>').appendTo(item);

                        st = stime.replace(/:/g, "");
                        lt = ltime.replace(/:/g, "");
                        if (st < current_time && current_time < lt) {
                            item.css("background-color", "#00C5CC");
                            console.log(st + "a" + lt + " " + current_time);
                        }
                        else if (lt < current_time) {
                            item.css("background-color", "#D3D3D3");
                            console.log(st + "b" + lt + " " + current_time);
                        }
                        else {
                            item.css("background-color", "#577cbd");
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
                        $('<div class="title"></div>').text(title).appendTo(itemtext);
                        $('<div class="place"></div>').text(pname).appendTo(itemtext);
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'latitude',
                            value: latitude
                        }).appendTo(itemtext);
                        $('<input>').attr({
                            type: 'hidden',
                            id: 'longitude',
                            value: longitude
                        }).appendTo(itemtext);
                        item.appendTo($('#leftSide'));

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

                var marker = new google.maps.Marker({
                    position: locations[i].latlng,
                    map: mymap,
                    title: locations[i].name
                });
                bounds.extend(locations[i].latlng);
                bindInfoWindow(marker, mymap, infowindow, schedule[i]);
            }

            function bindInfoWindow(marker, map, infowindow, data) {
                marker.addListener('mouseover', function () {
                    infowindow.setContent("<h5>" + data.title + "</h5><p>" + data.startTime + "~" + data.lastTime + "</p><p>" + data.content + "</p>");
                    infowindow.open(map, this);
                });
                marker.addListener('mouseout', function () {
                    infowindow.close(map, this);
                });

            }

            mymap.setCenter(bounds.getCenter());
            mymap.fitBounds(bounds);  //  all marker show on map
        }


        function geolocationError(positionError) {
//            document.getElementById("error").innerHTML += "Error: " + positionError.message + "<br />";
        }

        function geolocateUser() {
            if (navigator.geolocation) {

                var positionOptions = {
                    enableHighAccuracy: true,
                    timeout: 10 * 1000 // 10seconds
                };

                navigator.geolocation.getCurrentPosition(geolocationSuccess, geolocationError, positionOptions)
            } else {
                console.log("fuck");
            }
        }


        $(document).on('click', '.Item', function () {
            var a = $(this).children().children('#latitude').val();
            var b = $(this).children().children('#longitude').val();
            var scheduleSet = new google.maps.LatLng(a, b);
            mymap.setZoom(15);
            for (var i = 0; i < locations.length; i++) {

                if (locations[i].latlng.latitude == scheduleSet.latitude && locations[i].latlng.latitude == scheduleSet.longitude) {

                    mymap.panTo(scheduleSet);
                }
            }
        });
        $(document).on('mouseover', '.Item', function () {
            $(this).css("opacity", 0.7);
        });
        $(document).on('mouseout', '.Item', function () {
            $(this).css("opacity", 1.0);

        });

    </script>

</head>
<body>
<?php require_once "application/views/common/header_main.php"; ?>

<!--    -->
<?//php require_once "application/views/main/common/rightSide_todo.php";?>
<main style="overflow-y: hidden;\">
    <div id="MAP_CANVAS" style="height:100%; width:88%; position: absolute">
        <div id='leftSide'></div>
        <div class="container-fluid" id="mymap"></div>
        <div id="driving_info"></div>

    </div>
</main>
</body>
</html>
