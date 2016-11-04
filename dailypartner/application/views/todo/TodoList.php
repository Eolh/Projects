<?php
$check = 1;
$page = 2;
?>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Bookblock: A Content Flip Plugin - Demo 4"/>
    <meta name="keywords" content="javascript, jquery, plugin, css3, flip, page, 3d, booklet, book, perspective"/>
    <meta name="author" content="Codrops"/>

    <link rel="stylesheet" href="/public/export_modules/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/public/css/bookblock.css"/>
    <!-- custom todoList style -->
    <link rel="stylesheet" type="text/css" href="/public/css/todoList.css"/>
    <link rel="stylesheet" type="text/css" href="/public/css/dncalendar-skin.min.css"/>
    <link rel="stylesheet" href="/public/css/main_header.css">
    <link rel="stylesheet" href="/public/export_modules/jquery-ui/jquery-ui.css">

    <link rel="stylesheet" type="text/css" href="/public/export_modules/dist/css/bootstrap-material-design.css">
    <link rel="stylesheet" type="text/css" href="/public/export_modules/dist/css/ripples.min.css">

    <script src="/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="/public/export_modules/jquery-ui/jquery-ui.js"></script>
    <script src="/public/js/jquerypp.custom.js"></script>
    <script src="/public/js/modernizr.custom.js"></script>
    <script src="/public/js/jquery.bookblock.js"></script>
    <script src="/public/js/command.js"></script>
    <script src="/public/export_modules/dist/js/material.min.js"></script>
    <script src="/public/export_modules/dist/js/ripples.min.js"></script>
    <script src="/public/js/dncalendar.js"></script>
</head>
<body>
<?php require_once "application/views/common/header_main.php"; ?>
<div id="List">
    <i class="fa fa-times" id="TodoClose" onclick="TodoClose()"></i>
    <i class="fa fa-times" id="FileClose" onclick="FileClose()"></i>

    <div id="ViewList">

    </div>
</div>
<div style="margin: 5%; height: 80%;">
    <div style="min-width: inherit; height: 100%;">
        <?php require_once "application/views/todo/Note_todo.php";?>
    </div>
</div>
</body>
</html>