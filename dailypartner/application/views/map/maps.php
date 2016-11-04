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
    <link rel="stylesheet" href="/public/css/sideTodo.css">

    <!-- end:   CSS -->

    <!--================================== start: script =================================-->
    <script src="/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="/public/export_modules/jquery-ui/jquery-ui.js"></script>
    <script src="/public/export_modules/bootstrap/js/bootstrap.min.js"></script>

    <!-- Todolist -->
    <script src="/public/js/modernizr.custom.js"></script>
    <script src="/public/js/jquerypp.custom.js"></script>
    <script src="/public/js/jquery.bookblock.js"></script>

    <!-- Bootstrap Material Js -->
    <script src="/public/export_modules/dist/js/material.min.js"></script>
    <script src="/public/export_modules/dist/js/ripples.min.js"></script>

    <!-- map -->
    <script type="text/javascript"
            src="https://maps.google.com/maps/api/js?key=AIzaSyCYCSJRgNsqrQbsTQhw1FUb6FhhhZXI3y4&v=3.23&language=ja&libraries=places"
            async defer></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <!-- command -->
    <script src="/public/js/command.js"></script>

    <!-- This page -->
    <script src="/public/js/map.js"></script>

    <!-- end:   script -->
</head>
<body style="overflow: hidden;">

<?php require_once "application/views/common/header_main.php"; ?>

<main>
<!--    --><?php //require_once "application/views/main/common/rightSide_todo.php"; ?>

    <div id="List">
        <i class="fa fa-times" id="TodoClose" onclick="TodoClose()"></i>
        <i class="fa fa-times" id="FileClose" onclick="FileClose()"></i>

        <div id="ViewList"></div>
    </div>

    <div id="MAP_CANVAS">

        <!--navi-->
        <div id="leftSide" class="map_navi btn-group-vertical">
            <div class="date">
                <img src="/public/img/arrow_left.png" class="datemove" id="preday" style="float: left;padding-left: 15px;padding-top: 8px">
                <span id="navi_title"></span><input id="datepicker" style="display: none">
                <img src="/public/img/arrow_right.png" class="datemove" id="nextday" style="float: right;padding-right: 15px;padding-top: 8px">
            </div>
            <!--Itemlist-->
        </div>

        <div id="mymap"></div>  <!-- map view -->



    </div>

</main>
</body>
</html>