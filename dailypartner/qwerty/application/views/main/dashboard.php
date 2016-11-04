<!DOCTYPE html>
<html>

<head>

    <!-- start: PAGE SETTINGS -->
    <title></title>
    <meta charset="utf-8">
    <!-- end:   PAGE SETTINGS -->

    <!-- start: META -->
    <!-- end:   META -->

    <!-- start: CSS -->
    <link rel="stylesheet" href="http://localhost:8088/public/css/header_dashboard.css">
    <link rel="stylesheet" href="http://localhost:8088/public/css/dashboard_main.css">
    <link rel="stylesheet" href="http://localhost:8088/public/css/sideTodo.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <!--팝업달력-->
    <link rel="stylesheet" href="http://localhost:8088/public/css/registSchedule.css">
    <link rel="stylesheet" href="http://localhost:8088/public/export_modules/bootstrap/css/bootstrap.css">
    <style>

        body
        {
            width  : 1200px;
            height : 900px;
        }

        #space-left, #space-middle, #space-right
        {
            width      : 31%;
            min-height : 100px;
            padding    : 1%;
            float      : left;
            border     : 1px dotted black;
        }

        #dashBoard .block:active
        {
            color : red;
        }

        #space-middle .block, #space-left .block, #space-right .block
        {
            width  : 100%;
            border : 1px solid silver;
        }

        #space-left, #space-middle, #space-right
        {
            display : inline-flex;
            margin  : 30px;
        }

        img
        {
            width  : 33%;
            height : 100%;
        }
    </style>
    <!-- end:   CSS -->

    <!-- start: font -->
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Cinzel'>
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Bitter'>
    <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Special+Elite'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
    <!-- end:   font -->

    <!-- start: script -->
    <!--
    <script src="http://localhost:8088/public/export_modules/"></script>
    -->
    <script src="http://localhost:8088/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="http://localhost:8088/public/export_modules/jquery-ui/jquery-ui.js"></script>
    <script src="http://localhost:8088/public/export_modules/jquery.media.js"></script>
    <script src="http://localhost:8088/public/export_modules/jquery.metadata.js"></script>
    <script src="http://localhost:8088/public/export_modules/jquery.gdocsviewer.js"></script>
    <script src="http://localhost:8088/public/js/dashboard.js"></script>
    <!--jQuery UI Datepicker - Icon trigger-->
    <script>
        $(function () {
            $("#datepicker").datepicker({
                showOn          : "button",
                buttonImage     : "http://localhost:8088/public/img/calendar.png",
                buttonImageOnly : true,
                buttonText      : "Select date"
            });
        });
    </script>
    <!--toggleClass Demo-->
    <script>
        $(function () {
            $("#TodoBtn").click(function () {
                $("#slideE").toggleClass("sideTodo", 1000);
            });
        });

        $(function() {
            $('a.media').media({width:500, height:400});
        });

        $(function() {
           $("a.media2").gdocsViewer({width:500, height:400});
        });
    </script>
    <!-- end:   script -->
</head>
<body>
<?php require_once "application/views/common/header_main.php"; ?>

<br> <!-- 나중에 알아서 css로 맞추고 지울 것 -->

<main>

    <a class="media" href="http://localhost:8088/public/file/foway/1.pdf">PDF File</a>
    <a class="media2" href="http://localhost:8088/public/file/foway/1.pdf">PDF File</a>

    <div>

        <fieldset>
            <legend>
                To-do List
            </legend>

            <h1> 친구 등록 </h1>
            <h1> 그룹 시간 </h1>
            <h1> 분석 </h1>
            <h1> 파일 뷰어 </h1>
            <h1> 숏 커맨드 </h1>
            <h1>
                <?=getArgDump($_SESSION);?>
            </h1>
        </fieldset>

    </div>

    <div id="dashBoard">
        <div id="space-left" class="item">
            <div class="block" id="1">
                A
                <img src="../public/img/icon1.jpg">
            </div>
            <div class="block" id="2">
                <a href="/file/">file</a>
            </div>
            <div class="block" id="3">
                <a href="/calendar/">calendar</a>
                <?php

                echo $cal;

                ?>
            </div>
        </div>

        <div id="space-middle" class="item">
            <div class="block" id="4">
                D
            </div>
            <div class="block" id="5">
                E
            </div>
            <div class="block" id="6">
                F
            </div>
        </div>

        <div id="space-right" class="item">
            <div class="block" id="7">
                G
            </div>
            <div class="block" id="8">
                H
            </div>
            <div class="block" id="9">
                I
            </div>
        </div>
    </div>

    <div class="slideDiv">
        <div id="slideE" class="sideTodo">
            <div class="A">
                <p class="Todo-title">To-do Add</p>
                <ul>
                    <li><input type="text"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></li>
                    <li><p>~ <input type="text" id="datepicker"></p></li>
                </ul>
            </div>
            <div class="B">
                <p class="Todo-title">5/10</p>

                <div class="block-a">
                    <!--데드라인 있는 to-do-->
                    date
                </div>
                <hr class="line">
                <!--구분선-->
                <div class="block-b">
                    <!--데드라인 없는 to-do, 자유공간-->
                    free
                </div>
            </div>
        </div>
        <button id="TodoBtn"><i class="fa fa-angle-double-right" aria-hidden="true"></i></button>
    </div>
</main>
<script>/*top-menu 높이에따른 변동*/
    $(window).resize(function () {
        var height = $('header').css('height');
        $('main').css('marginTop', height);
    });
</script>
</body>

</html>