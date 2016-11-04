<!DOCTYPE html>
<html>

<head>

    <!-- start: PAGE SETTINGS -->
    <title> dashBoard Test!!! </title>
    <meta charset="utf-8">
    <!-- end:   PAGE SETTINGS -->

    <!-- start: META -->
    <!-- end:   META -->

    <!-- start: CSS -->
    <link rel="stylesheet" href="http://localhost:8088/public/export_modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://localhost:8088/public/export_modules/jquery-ui/jquery-ui.css">
    <!-- end:   CSS -->

    <!-- start: font -->
    <!-- end:   font -->

    <!-- start: script -->
    <script src="http://localhost:8088/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="http://localhost:8088/public/export_modules/jquery-ui/jquery-ui.js"></script>
    <!-- end:   script -->

    <style>

        #dashBoard, .draggable
        {
            font-size : 60px;
            border    : 1px solid black;
            display   : inline-block;
            width     : 900px;
        }

        #space-left, #space-middle, #space-right
        {
            width      : 33%;
            min-height : 100px;
            padding    : 1%;
            float      : left;
            border     : 3px dotted black;
        }

        .draggable
        {
            width      : 33%;
            min-height : 100px;
            padding    : 1%;
            float      : left;
            position   : inherit;
        }

        #dashBoard .block:active
        {
            color : red;
        }

        #space-middle .block, #space-left .block, #space-right .block
        {
            width  : 100%;
            height : 100px;
        }

        .close
        {
            font-size : 20px;
            border    : 1px solid black;
        }

        img { width: 80px; }

    </style>

    <script>

        $(function() {
            $("#space-left, #space-middle, #space-right").sortable({
                connectWith: ".item",
                revert : "invalid"
            }).disableSelection();
            /* sortable */

            $(".close").click(function() {
                $(this).parent().remove();
            });
            /* clicker */

            $(".draggable").draggable({
                connectToSortable : "#space-left, #space-middle, #space-right",
                helper : "clone",   /* div의 부드러움 정도를 표현 */
                revert : "false", /* 동작의 부드러움 정도를 표현 */
                cursorAt : {top : 0.5, left: 0.5},
                start : function(event, ui) {
                    $(ui.helper).attr("class", "block ui-sortable-handle");
                    $(ui.helper).css({"width":"100px"});
                },
                stop  : function(event, ui) {
                    $(ui.helper).css({"width":"auto"});
                    $(ui.helper).append("<a class='close' onclick='$(this).parent().remove();'>X</a>");
                }
            });
            /* draggable */
        });

    </script>

</head>
<body>
<div class="container" align="center">

    <h1>
        Hello It's a DashBoard Test!!!
    </h1>

    <div id="dashBoard">

        <div id="space-left" class="item">
            <div class="block">
                A
                <a class="close">X</a>
            </div>
            <div class="block">
                B
                <a class="close">X</a>
            </div>
            <div class="block">
                C
                <a class="close">X</a>
            </div>
        </div>

        <div id="space-middle" class="item">
            <div class="block">
                D
                <a class="close">X</a>
            </div>
            <div class="block">
                E
                <a class="close">X</a>
            </div>
            <div class="block">
                F
                <a class="close">X</a>
            </div>
        </div>

        <div id="space-right" class="item">
            <div class="block">
                G
                <a class="close">X</a>
            </div>
            <div class="block">
                H
                <a class="close">X</a>
            </div>
            <div class="block">
                I
                <a class="close">X</a>
            </div>
        </div>

    </div>

    <div id="dashBoardDock">
        <div class="draggable">
            <img src="http://localhost:8088/public/img/icon1.jpg">
        </div>
        <div class="draggable">
            <img src="http://localhost:8088/public/img/icon2.jpg">
        </div>
        <div class="draggable">
            <img src="http://localhost:8088/public/img/icon3.jpg">
        </div>
    </div>

</div>
</body>

</html>