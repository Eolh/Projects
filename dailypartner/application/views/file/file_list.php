<!DOCTYPE html>
<html>
<head>

    <!-- start: PAGE SETTINGS -->
    <title>balancemyschedule</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <link rel="stylesheet" type="text/css" href="/public/css/dncalendar-skin.min.css"/>
    <link rel="stylesheet" type="text/css" href="/public/css/todoList.css"/>

    <link rel="stylesheet" href="/public/css/main_header.css">

    <link rel="stylesheet" href="/public/css/file_list.css">
    <!-- end:   CSS -->

    <!--================================== start: script =================================-->
    <script src="/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="/public/export_modules/jquery-ui/jquery-ui.js"></script>
    <script src="/public/export_modules/bootstrap/js/bootstrap.min.js"></script>

    <!-- Todolist & Command -->
    <script src="/public/js/modernizr.custom.js"></script>
    <script src="/public/js/jquerypp.custom.js"></script>
    <script src="/public/js/jquery.bookblock.js"></script>
    <script src="/public/js/dncalendar.js"></script>

    <!-- Bootstrap Material Js -->
    <script src="/public/export_modules/dist/js/material.min.js"></script>
    <script src="/public/export_modules/dist/js/ripples.min.js"></script>

    <!--material동작처리-->

    <script src="/public/js/command.js">

    </script>
    <script>
        $(document).ready(function () {
            $.material.init();

            $("#search").keyup(function (e) {


                var regexSize = /^[A-Za-z0-9ㄱ-ㅎ가-힣\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]*$/i;
                var search    = $("#search").val();

                if(search.substr(0,1) == "/")
                    return false;

                if(regexSize.test(search)) {

                    var tag = $("#selectedTag").val();

                    console.log(search + " : " + tag);


                    $.ajax({
                        type : "POST",
                        url  : "/file/fileListerSearch",
                        data : {
                            search : search, tag : tag
                        },
                        success : function(data) {

                            $("#fileLister").empty();
                            $("#fileLister").append(data);
                        },
                        error   : function(data) {

                        }
                    });
                }

                /* 한글 영어 특수문자일 경우. */
            });
        });

        $(window).resize(function () {
            var height = $('.navbar').css('height');
            $('main').css('marginTop', height);
        });

        function schedule(data) {
            $("#schedule").modal("show");

            var test = data;
            console.log(test.FName);
            $("#title").text(test.title);
            $("#staTime").text(test.startTime);
            $("#endTime").text(test.lastTime);
            $("#content").text(test.content);
            $("#file").empty();
            $("#file").append(
                "<iframe src='https://docs.google.com/gview?url=https://www.dailypartner.xyz/public/file/foway/" + test.FName + "&embedded=true' style='width:100%; height:450px;' frameborder='0'>" + "</iframe>"
            );
           console.log("<iframe src='https://docs.google.com/gview?url=https://www.dailypartner.xyz/public/file/foway/" + test.FName + "&embedded=true' style='width:100%; height:450px;' frameborder='0'>");
        }

        file_list('<?=isset($selectedName) ? $selectedName : null; ?>');

        function file_list(tag) {
            $.ajax({
                url     : '/file/fileLister',
                type    : 'POST',
                data    : {
                    tag : tag
                },
                success : function (data) {

                    if(tag == "")
                        tag = "ALL";

                    $("#selectedTag").html(tag);
                    $("#fileLister").empty();
                    $("#fileLister").append(data);
                }
            });
        }

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <!-- end:   script -->

</head>
<body>

<div id="List">
    <i class="fa fa-times" id="TodoClose" onclick="TodoClose()"></i>
    <i class="fa fa-times" id="FileClose" onclick="FileClose()"></i>

    <div id="ViewList">

    </div>
</div>

<?php require_once "application/views/common/header_main.php"; ?>

<main>
    <div class="main-paper">
        <div>
            <h3><span class="glyphicon glyphicon-list-alt"></span> to-do list</h3>
        </div>
        <div class="row">
            <!--table list-->
            <div class="col-lg-3 col-sm-3 col-xs-12">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th class="table-title">TAG LIST</th>
                    </tr>
                    <tr>
                        <th>SELECTED TAG:
                            <a id="selectedTag" class="btn" onclick="file_list('<?= $selectedName ?>')" style="margin: 0">
                                <?= $selectedName = ($selectedName != null) ? $selectedName : "ALL" ?>
                            </a>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td style=" background-color: rgba(224, 232, 232, 0.5);">
                            <a href="/file/" style="margin: 0;">
                                <p style="padding: 5px 8px; margin: 0;">all
                                    <span class="badge" style="float: right">총태그 수 : <?= count($tagList); ?></span>
                                    <span class="badge" style="float: right">총파일 수 : <?= $cntFiles; ?></span>
                                </p>
                            </a>
                        </td>
                    </tr>
                    <?php
                    foreach ($tagList as $tag)
                    { ?>
                        <tr>
                            <td>
                                <a onclick="file_list('<?= $tag->tagName; ?>')">
                                    <p style="padding: 5px 8px; margin: 0;"><?= $tag->tagName; ?>
                                        <span class="badge" style="float: right"><?= $tag->fileList->cnt; ?></span>
                                    </p>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="text-center" style="border-bottom: 2px solid #ddd;">more tag...
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!--file list-->
            <div class="col-lg-9 col-sm-9 col-xs-12">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th colspan="7" class="table-title table-title-s1">FILE LIST</th>
                    </tr>
                    <tr class="row">
                        <th class="col-sm-1 text-center">NUM</th>
                        <th class="col-sm-3">NAME [download]</th>
                        <th class="col-sm-1">SIZE</th>
                        <th class="col-sm-2">TAG</th>
                        <th class="col-sm-3">DATE</th>
                        <th class="col-sm-2">SCHEDULE</th>
                    </tr>
                    </thead>
                    <!--contents list-->
                    <tbody id="fileLister">
                    </tbody>
                </table>
            </div>
        </div>

        <!--maybe modal-->
        <?php require_once "application/views/file/file_schedule.php"; ?>
    </div>
</main>
</body>
</html>