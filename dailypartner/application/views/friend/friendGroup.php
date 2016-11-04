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
    <!--  <link rel="stylesheet" href="/public/export_modules/jquery-ui/jquery-ui.css">
      <link rel="stylesheet" type="text/css" href="/public/css/bookblock.css"/>
      <link rel="stylesheet" type="text/css" href="/public/css/dncalendar-skin.min.css"/>
      <link rel="stylesheet" type="text/css" href="/public/css/todoList.css"/>-->

    <link rel="stylesheet" href="/public/css/main_header.css">
    <link rel="stylesheet" href="/public/css/file_list.css">
    <!-- end:   CSS -->

    <!--================================== start: script =================================-->
    <script src="/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="/public/export_modules/jquery-ui/jquery-ui.js"></script>
    <script src="/public/export_modules/bootstrap/js/bootstrap.min.js"></script>

    <!-- Todolist & Command -->
    <!--<script src="/public/js/modernizr.custom.js"></script>
    <script src="/public/js/jquerypp.custom.js"></script>
    <script src="/public/js/jquery.bookblock.js"></script>
    <script src="/public/js/dncalendar.js"></script>-->

    <!-- Bootstrap Material Js -->
    <script src="/public/export_modules/dist/js/material.min.js"></script>
    <script src="/public/export_modules/dist/js/ripples.min.js"></script>

    <!--material동작처리-->

    <script src="/public/js/FriendAndGroup.js"></script>
    <script src="/public/js/command.js"></script>
    <script>
        $(document).ready(function () {
            $.material.init();

            $("#search").keyup(function (e) {

                var regexSize = /^[A-Za-z0-9ㄱ-ㅎ가-힣\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]*$/i;
                var search = $("#search").val();

                if (search.substr(0, 1) == "/")
                    return false;

                if (regexSize.test(search)) {

                    var tag = $("#selectedTag").text();

                    console.log(search + " : " + tag);

                    $.ajax({
                        type: "POST",
                        url: "/file/fileListerSearch",
                        data: {
                            search: search, tag: tag
                        },
                        success: function (data) {

                            $("#fileLister").empty();
                            $("#fileLister").append(data);
                        },
                        error: function (data) {

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

        file_list('<?=isset($selectedName) ? $selectedName : null; ?>');

        function file_list(tag) {
            $.ajax({
                url: '/file/fileLister',
                type: 'POST',
                data: {
                    tag: tag
                },
                success: function (data) {

                    if (tag == "")
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
            <h3><i class="material-icons">supervisor_account</i> 友達及びグループ</h3>
        </div>
        <div class="row">
            <!--friend list-->
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th colspan="3" class="table-title">FRIEND LIST</th>
                    </tr>
                    </thead>

                    <tbody>
                    <!--친구 신청 목록-->
                    <?php
                    if ($friendRequest) {
                        foreach ($friendRequest as $friend) { ?>
                            <tr id=AddList<?= $friend->UID ?>>
                                <td class="col-sm-1 col-xs-1" style="vertical-align: middle;">
                                    <div class="row-picture">
                                        <img class="circle" src="/public/img/NULL.png" alt="icon"
                                             style="height: 56px; width: 56px; border: solid 1px #e0e8e8; opacity: 0.5">
                                        <!-- src="" -->
                                    </div>
                                </td>
                                <td class="col-lg-8 col-xs-8">
                                    <p style="padding: 5px 8px; margin: 0;"><?= $friend->name . " ( " . $friend->ID . " ) "; ?>
                                    </p>

                                    <p style="padding: 5px 8px; margin: 0;"><i class="fa fa-phone-square"
                                                                               aria-hidden="true"></i>&nbsp;<?= $friend->Tel; ?>

                                </td>
                                <td class="col-lg-3 col-xs-3" style="margin-right: 8px; vertical-align: middle;">
                                    <a onclick="friendaddlist_delete('<?= $friend->UID; ?>')"
                                       title="<?= $friend->UID; ?>"
                                       class="btn btn-fab btn-default"
                                       style="height: 40px; min-width: 40px; width: 40px; color: #c76047; float: right; margin-left: 10px;"><i
                                            class="material-icons" style="font-weight: bolder">remove</i></a>
                                    <a onclick="friendaddlist_add('<?= $friend->UID; ?>')" title="<?= $friend->UID; ?>"
                                       class="btn btn-fab"
                                       style="height: 1.7em; min-width: 1.7em; width: 1.7em; color: #00c5cc; float: right;"><i
                                            class="material-icons" style="font-weight: bolder">done</i></a>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="3" align="center"><h4>新しい友達リクエストはありません</h4></td>
                        </tr>
                    <?php } ?>
                    <tr id=FriendList>
                        <th colspan="3" class="info"></th>
                    </tr>
                    <!--친구 목록-->
                    <?php
                    foreach ($friendList as $friend) { ?>
                        <tr id=friend<?= $friend->UID ?>>
                            <td class="col-sm-1" style="vertical-align: middle">
                                <div class="row-picture">
                                    <img class="circle" src="/public/img/NULL.png" alt="icon" style="opacity: 0.5;">
                                </div>
                            </td>
                            <td class="col-lg-8 col-sm-8">
                                <p style="padding: 5px 8px; margin: 0;"><?= $friend->name . " ( " . $friend->ID . " ) "; ?>
                                </p>

                                <p style="padding: 5px 8px; margin: 0;"><i class="fa fa-phone-square"
                                                                           aria-hidden="true"></i>&nbsp;<?= $friend->Tel; ?>

                            </td>
                            <td class="col-lg-3 col-sm-3" style="margin-right: 8px; vertical-align: middle;">
                                <a id="add_friend_in_group<?= $friend->UID; ?>" class="btn btn-fab"
                                   style="height: 40px; min-width: 40px; width: 40px; float:right;">
                                    <i class="material-icons" style="font-weight: bolder">add</i></a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

            <!--group list-->
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th class="table-title table-title-s1" colspan="3">GROUP LIST</th>
                    </tr>
                    <!--Add Group-->
                    <tr class="form-group" style="margin: 0; padding: 0">

                        <th class="input-group col-sm-12" colspan="2">
                            <input class="form-control col-sm-9" type="text" name="tab_title" id="Group_name"
                                   placeholder="Group Nmae" style="text-indent: 20px">
                        </th>
                        <th>
                            <span class="input-group-btn col-sm-4">
                                <button class="btn btn-primary" type="button" id="add_group" onclick="Addgroup()">+ Add
                                    group
                                </button>
                            </span>
                        </th>
                    </tr>
                    <!--Print GroupList-->
                    <?php
                    if ($groupList) { ?>
                        <tr>
                            <th colspan="3">
                                <ul class="nav nav-tabs" id="group_add">
                                    <!--여기에 추가된 group 붙음-->
                                    <?php foreach ($groupList as $groupInfo) {
                                        for ($i = 0; $i < count($groupInfo); $i++) {
                                            ?>
                                            <!-- Nav tabs -->
                                            <li role="presentation" id="<?= $groupInfo[$i]->gname; ?>"
                                                onclick="grouplistView('<?= $groupInfo[$i]->gnum; ?>')">
                                                <a><?= $groupInfo[$i]->gname; ?></a>
                                            </li>
                                        <?php }
                                    } ?>
                                </ul>
                            </th>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <th style="text-align: center" colspan="3"><h4>Please Add Group</h4></th>
                        </tr>
                    <?php } ?>
                    </thead>

                    <!--Group Member List-->
                    <!--replaceWith함수로 tbody-->
                    <tbody class="tab-content" id="group_list">
                    <div class="tab-pane row">
                        <tr>
                            <td style="text-align: center" colspan="3">
                                select group tab<!--친구추가 방식 소개-->
                            </td>
                        </tr>
                    </div>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
</body>
</html>