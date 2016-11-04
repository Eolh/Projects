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

    <!-- Bootstrap Material Js -->
    <script src="/public/export_modules/dist/js/material.min.js"></script>
    <script src="/public/export_modules/dist/js/ripples.min.js"></script>

    <!--material동작처리-->
    <script>
        $(document).ready(function() {
            $.material.init();

            var gnum = '<?=$gnum?>';

            $("#search").keyup(function() {

                var regexSize = /^[A-Za-z0-9ㄱ-ㅎ가-힣\{\}\[\]\/?.,;:|\)*~`!^\-_+<>@\#$%&\\\=\(\'\"]*$/i;
                var search    = $("#search").val();

                if(search.substr(0,1) == "/")
                    return false;

                if(regexSize.test(search)) {

                    var test = $("li[role='presentation'][class='active'] a").attr('href');

                    if(test == "#home") {
                        var tag = $("#selectedTag").text();

                        $.ajax({
                            type : "POST",
                            url  : "/file/fileListerSearch",
                            data : {
                                search : search, tag : tag
                            },
                            success : function(data) {

                                $("#fileLister").empty();
                                $("#fileLister").append(data);
                            }
                        });

                    } else {

                        //var name  = $("#GroupSelectedTag").text();
                        var gm_id = $("#GroupSelectedTag").attr('gm_id');

                        $.ajax({
                            type : "POST",
                            url  : "/group/fileListerGroupSearch",
                            data : {
                                search : search, gnum : gnum, gm_id : gm_id
                            },
                            success : function(data) {

                                $("#groupFileLister").empty();
                                $("#groupFileLister").append(data);
                            }
                        });
                    }
                }
            });

            file_list('<?=isset($selectedName) ? $selectedName : null; ?>');
            file_groupList('', '<?=$gnum?>');
        });

        var gnum = '<?=$gnum?>';

        function file_list(tag) {

            $.ajax({
                url     : '/group/fileLister',
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

        function group_schedule(data) {
            $("#gp_schedule").modal("show");

            var test = data;

            $("#title").text(test.title);
            $("#staTime").text(test.startTime);
            $("#endTime").text(test.lastTime);
            $("#content").text(test.content);
            $("#file").empty();
            $("#file").append(
                "<iframe src='https://docs.google.com/gview?url=https://133.130.125.87/public/file/group_" + gnum + "/" + test.FName + "&embedded=true' style='width:100%; height:450px;' frameborder='0'>" + "</iframe>"
            );
        }

        function file_groupList(gm_id, gnum) {

            $.ajax({
                url     : '/group/fileGroupList',
                type    : 'POST',
                data    : {
                    gm_id : gm_id,
                    gnum  : gnum
                },
                success : function (data) {

                    console.log(data);

                    if(gm_id == "") {
                        $("#GroupSelectedTag").html("全員");
                        $("#GroupSelectedTag").attr('onclick', 'file_groupList("", ' + gnum + ')');
                        $("#GroupSelectedTag").attr('gm_id', 'all');
                    } else {
                        $.ajax({
                            url     : '/group/getMember',
                            type    : 'POST',
                            data    : {
                                gm_id : gm_id
                            },
                            success : function(data) {

                                $("#GroupSelectedTag").attr('onclick', 'file_groupList("' + gm_id + '", "' + gnum + '")');
                                $("#GroupSelectedTag").html(data);
                                $("#GroupSelectedTag").attr('gm_id', gm_id);
                            }
                        });
                    }

                    $("#groupFileLister").empty();
                    $("#groupFileLister").append(data);
                }
            });
        }
    </script>
</head>
<body>

<?php require_once "application/views/common/header_main.php"; ?>

<main>
    <div class="main-paper">

        <div class="well main-paper" id="tabList">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist" id="myTabs">
                <li role="presentation" class="active">
                    <a href="#home" aria-controls="home" role="tab"
                        data-toggle="tab">Home</a>
                </li>
                <!-- inserted groupTabList -->
                <li role="presentation">
                    <a href="#group" role="tab" data-toggle="tab">
                        <?=$groupList->gname?>
                    </a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home">
                    <div class="row">
                        <div class="col-lg-3 col-sm-3 col-xs-12">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th class="table-title">タグのリスト</th>
                                </tr>
                                <tr>
                                    <th>見ている タグ:
                                        <a id="selectedTag" class="btn" onclick="file_list('<?= $selectedName ?>')" style="margin: 0">
                                            <?= $selectedName = ($selectedName != null) ? $selectedName : "ALL" ?>
                                        </a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style=" background-color: rgba(224, 232, 232, 0.5);">
                                        <a href="/group/file/<?=$gnum?>" style="margin: 0;">
                                            <p style="padding: 5px 8px; margin: 0;">all
                                                <span class="badge" style="float: right">タグの数 : <?= count($tagList); ?></span>
                                                <span class="badge" style="float: right">ファイルの数 : <?= $cntFiles; ?></span>
                                            </p>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                foreach($tagList as $tag) { ?>
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

                        <div class="col-lg-9 col-sm-9 col-xs-12">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th colspan="7" class="table-title table-title-s1">ファイルのリスト</th>
                                </tr>
                                <tr class="row">
                                    <th class="col-sm-1 text-center">番号</th>
                                    <th class="col-sm-3">名 [ダウンロード]</th>
                                    <th class="col-sm-1">サイズ</th>
                                    <th class="col-sm-2">タグ</th>
                                    <th class="col-sm-3">登録日</th>
                                    <th class="col-sm-2">スケジュール</th>
                                </tr>
                                </thead>
                                <!--contents list-->
                                <tbody id="fileLister">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div role="tabpanel" class="tab-pane fade" id="group">
                    <div class="row">
                        <div class="col-lg-3 col-sm-3 col-xs-12">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th class="table-title">メンバーのリスト</th>
                                </tr>
                                <tr>
                                    <th>見ている　メンバー:
                                        <a gm_id="all" id="GroupSelectedTag" class="btn" onclick="file_groupList('', '<?=$gnum?>')" style="margin: 0">
                                            全部
                                        </a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style=" background-color: rgba(224, 232, 232, 0.5);">
                                        <a onclick="file_groupList('', '<?=$gnum?>')" style="margin: 0;">
                                            <p style="padding: 5px 8px; margin: 0;">全部
                                                <span class="badge" style="float: right">ファイルの数 : <?= $groupCntFiles; ?></span>
                                            </p>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                foreach($groupList->memberList as $member) { ?>
                                    <tr>
                                        <td>
                                            <a onclick="file_groupList('<?=$member->gm_id;?>', '<?=$gnum?>')">
                                                <p style="padding: 5px 8px; margin: 0;"><?= $member->name; ?>
                                                    <span class="badge" style="float: right"><?= $member->fileList->cnt; ?></span>
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

                        <div class="col-lg-9 col-sm-9 col-xs-12">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th colspan="7" class="table-title table-title-s1">ファイルのリスト</th>
                                </tr>
                                <tr class="row">
                                    <th class="col-sm-1 text-center">番号</th>
                                    <th class="col-sm-3">名 [ダウンロード]</th>
                                    <th class="col-sm-1">サイズ</th>
                                    <th class="col-sm-2">タグ</th>
                                    <th class="col-sm-3">登録日</th>
                                    <th class="col-sm-2">スケジュール</th>
                                </tr>
                                </thead>
                                <!--contents list-->
                                <tbody id="groupFileLister">
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php require_once "application/views/group/file_group_schedule.php"; ?>
                </div>
                <!-- inserted groupDashboard -->
            </div>
        </div>
    </div>
</main>

</body>
</html>