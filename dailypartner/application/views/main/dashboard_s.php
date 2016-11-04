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
    <link rel="stylesheet" href="/public/css/animate.css">

    <!-- Bootstrap Material Design -->
    <link rel="stylesheet" type="text/css" href="/public/export_modules/dist/css/bootstrap-material-design.css">
    <link rel="stylesheet" type="text/css" href="/public/export_modules/dist/css/ripples.min.css">

    <!-- Todolist & Command -->
    <link rel="stylesheet" href="/public/export_modules/jquery-ui/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="/public/css/bookblock.css"/>
    <link rel="stylesheet" type="text/css" href="/public/css/todoList.css"/>

    <link rel="stylesheet" href="/public/css/main_header.css">

    <!-- This page -->
    <link rel="stylesheet" href="/public/css/dashboard_main.css">
    <link rel="stylesheet" href="/public/css/weatherWidget.css">
    <link rel="stylesheet" href="/public/css/registSchedule.css">
    <link rel="stylesheet" href="/public/export_modules/fullcalendar/fullcalendar.css">
    <link rel="stylesheet" href="/public/css/fullcalendar.css">
    <link rel="stylesheet" href="/public/css/fullcalendar.print.css">
    <!--팝업달력-->
    <style>
        .cyka
        {
            background-color : #0dcc3f;
        }

        #external-events
        {
            float      : left;
            width      : 150px;
            padding    : 0 10px;
            text-align : left;
        }

        #external-events h4
        {
            font-size   : 16px;
            margin-top  : 0;
            padding-top : 1em;
        }

        .external-event
        {
            /* try to mimick the look of a real event */
            margin     : 10px 0;
            padding    : 2px 4px;
            background : #3366CC;
            color      : #fff;
            font-size  : .85em;
            cursor     : pointer;
        }

        #external-events p
        {
            margin    : 1.5em 0;
            font-size : 11px;
            color     : #666;
        }

        #external-events p input
        {
            margin         : 0;
            vertical-align : middle;
        }
    </style>
    <!-- end:   CSS -->

    <!--================================== start: script =================================-->
    <script src="/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="/public/export_modules/jquery-ui/jquery-ui.js"></script>
    <script src="/public/export_modules/bootstrap/js/bootstrap.min.js"></script>

    <script src="/public/export_modules/fullcalendar/lib/moment.min.js"></script>
    <script src="/public/export_modules/fullcalendar/fullcalendar.js"></script>
    <script src="/public/export_modules/fullcalendar/lang/ja.js"></script>
    <script src="/public/export_modules/jssor.slider.mini.js"></script>

    <!-- Todolist & Command -->
    <script src="/public/js/modernizr.custom.js"></script>
    <script src="/public/js/jquerypp.custom.js"></script>
    <script src="/public/js/jquery.bookblock.js"></script>

    <!-- Bootstrap Material Js -->
    <script src="/public/export_modules/dist/js/material.min.js"></script>
    <script src="/public/export_modules/dist/js/ripples.min.js"></script>

    <!-- This Page -->
    <script src="/public/js/weather.js"></script>
    <script type="text/javascript"
        src="https://maps.google.com/maps/api/js?v3.23&key=AIzaSyBrJB3StK72knzhX4-9IpdTH422LdhAndM&language=ja&libraries=places"
        async defer></script>

    <!--material동작처리-->
    <script src="/public/js/command.js"></script>
    <!-- end:   script -->

    <script src="/public/js/dashBoard.js"></script>
</head>

<body>
<?php require_once "application/views/common/header_main.php"; ?>

<main>
    <div id="List">
        <i class="fa fa-times" id="TodoClose" onclick="TodoClose()"></i>
        <i class="fa fa-times" id="FileClose" onclick="FileClose()"></i>

        <div id="ViewList"></div>
    </div>
    <!--브리핑-->
    <div class="background-texture">
        <div class="jumbotron"
            style="padding: 20px; color: #e0e8e8; background-color: rgba(39, 40, 44, 0.3); word-break: break-all;">
            <div class="briefing"></div>
        </div>
    </div>
    <div class="well main-paper" id="tabList">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs gradient-texture" role="tablist" id="myTabs">
            <li role="presentation" class="active">
                <a href="#home" aria-controls="home" role="tab"
                    data-toggle="tab">ホーム</a>
            </li>
            <!-- inserted groupTabList -->
            <li role="presentation">
                <a href="#" role="tab"
                    data-toggle="tab"><i class="fa fa-plus-circle" aria-hidden="true"></i></a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="home">
                <!--대시보드 그리드-->
                <div class="row dashboard-base">
                    <div class="col-sx-12 col-sm-12 col-lg-4 dashboard-left item">
                        <div class="col-sx-12 col-sm-6 col-lg-12 dashboard-box" id="dashboard-box-n1">
                            <div class="wiget panel" id="1">
                                <a href="/calendar/" style="color: #e0e8e8;"><p class="wiget-handle texture-img">カレンダー</p>
                                </a>

                                <div>
                                    <div id="monthlyCalendar"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-6 col-lg-12 dashboard-box" id="dashboard-box-n2">
                            <div class="wiget panel" id="2">
                                <a href="/maps/"><p class="wiget-handle texture-img">地図</p></a>

                                <div>
                                    <div id="maps" style="width: 100%; min-height: 230px"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sx-12 col-sm-6 col-lg-4 dashboard-center item">
                        <div class="col-sx-12 col-sm-12 col-lg-12 dashboard-box" id="dashboard-box-n3">
                            <div class="wiget panel" id="3">
                                <p class="wiget-handle texture-img">今日の日程</p>

                                <div>
                                    <div id="todayCalendar"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sx-12 col-sm-6 col-lg-4 dashboard-right item">
                        <div class="col-sx-12 col-sm-12 col-lg-12 dashboard-box" id="dashboard-box-n5">
                            <div class="wiget panel" id="5">
                                <a href="/TodoList/"><p class="wiget-handle texture-img">やることリスト</p></a>

                                <div style="min-height: 230px">
                                    <div id="TodoList2"
                                        style="height: 100%; overflow: auto; overflow-y:hidden;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-lg-12 dashboard-box" id="dashboard-box-n4">
                            <div class="wiget panel" id="4">
                                <p class="wiget-handle texture-img">天気</p>
                                <!--낮과 밤(구름)으로 나뉘어서 처리(w_sun.png/w_night.png), 33.png-->
                                <div id="weather_widget_bg" style="background: url('/public/img/w_sun.png'); background-size: cover;">
                                    <?php require_once "application/views/weatherWidget.php"; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-lg-12 dashboard-box" id="dashboard-box-n6">
                            <div class="wiget panel" id="6">
                                <a href="/file/"><p class="wiget-handle texture-img">ファイル</p></a>

                                <div>
                                    <div class="list-group" id="fileList"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- inserted groupDashboard -->
        </div>
    </div>
</main>
</body>
</html>