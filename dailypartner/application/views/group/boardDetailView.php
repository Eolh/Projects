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

    <link rel="stylesheet" href="/public/css/main_header.css">

    <!-- This page -->
    <link rel="stylesheet" href="/public/css/boardStyle.css">
    <link rel="stylesheet" href="/public/css/bodyStyle.css">
    <link rel="stylesheet" href="/public/css/mainStyle.css">

    <!-- end:   CSS -->

    <!--================================== start: script =================================-->
    <script src="/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="/public/export_modules/jquery-ui/jquery-ui.js"></script>
    <script src="/public/export_modules/bootstrap/js/bootstrap.min.js"></script>

    <!-- Bootstrap Material Js -->
    <script src="/public/export_modules/dist/js/material.min.js"></script>
    <script src="/public/export_modules/dist/js/ripples.min.js"></script>

    <!--material동작처리-->
    <script src="/public/js/command.js"></script>
    <!-- end:   script -->
</head>

<body style="margin-top: 50px">
<?php require_once "application/views/common/header_main.php"; ?>
<!--게시물 상세 내용 페이지-->
<main class="main-paper" style="max-width: 850px; margin: auto;">

    <table class="table table-striped row view_table" cellpadding="0" cellspacing="0" style="margin: auto">
        <!--본문-->
        <?php
        foreach ($detailView as $item) { ?>
            <thead>
            <tr class="view">
                <td width="70%" style="vertical-align: middle; font-weight: bold; font-size: 17px;">
                    # <?= $item->title; ?></td>
                <td class="view_s" style="vertical-align: middle"><?= $item->create_date; ?></td>
                <td class="view_s" style="vertical-align: middle; width:10%;">view <?= $hitCount; ?></td>
                <!--같은 id이면 조회수 중복제거-->
            </tr>
            <tr>
                <td>( <?= $item->create_by; ?> )</td>
                <td id="re_sel" colspan="2"><?= $groupName->gname;?></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td id="view_contents" colspan="3" style="padding: 30px 0 100px 0"><?= $item->content; ?></td>
            </tr>
            <?php if($ID->ID == $item->create_by){?>
            <tr>
                <td colspan="3"><button class="fa fa-trash btn" id="deleteBoard<?=$item->bnum?>" style="float:right;" onclick="deleteBoard('<?=$item->bnum?>','<?=$item->gnum?>')"></button></td>
            </tr>
            <?php } ?>
            <!--commant 덧글 입력-->
            <tr>
                <td colspan="3">
                    <input class="col-xs-10" id="enroll" type="text" style="height: 50px"
                           placeholder="Press 'Enter' is Commant registed">
                    <button class="col-xs-2 btn btn-raised btn-default" style="padding: 15px 0; margin: 0; border-radius: 0; border: 0" id="enrollBtn" onclick="enrollComment(<?= $detailView[0]->bnum ?>)">登録
                    </button>
                </td>
            </tr>
            </tbody>
        <?php } ?>
    </table>

    <!--commant 덧글 출력-->
    <table class="table table-striped table-hover row" id="list_commant" cellpadding="0" cellspacing="0"
           style="margin: auto">

        <?php
        if (!$commentView) { ?>
            <tr id="nonComment">
                <td align="center" colspan="3">登録したコメントがありません。</td>
            </tr>
        <?php } else {
            foreach ($commentView as $item) { ?>
                <tr id="comment<?= $item->cnum ?>">
                    <td class="col-xs-1" id="center" style="vertical-align: middle"><span
                            class="label label-primary"><?= $item->create_by; ?></span></td>
                    <td class="col-xs-9" id="comment_content"><?= $item->content; ?></td><!--</a>-->
                    <td class="col-xs-2" id="list_s" width="70"><?= $item->create_date; ?></td>
                    <?php if ($ID->ID == $item->create_by) { ?>
                        <td>
                        <button class="fa fa-trash btn" id="deleteComment<?= $item->cnum ?>"
                                onclick="deleteComment(<?= $item->cnum ?>)"></button></td><?php } ?>
                </tr>
            <?php }
        } ?>
    </table>
</main>
</body>
</html>

<script>

    function enrollComment(boardNum) {

        var content = $("#enroll").val();

        $.ajax({
            type: "POST",
            url: "/group/enrollComment",
            data: {
                'bnum': boardNum,
                'content': content
            },
            success: function (data) {

                var enrolldata = JSON.parse(data);

                var nonComment = $("#nonComment").attr('id');

                $("#list_commant").append("<tr class='info' id='comment" + enrolldata[0].cnum + "' style='vertical-align: middle'>" +
                    "<td class='col-xs-1' id='center'style='vertical-align: middle'><span class='label label-info'>" + enrolldata[0].create_by + "</span></td>" +
                    "<td class='col-xs-9' id='comment_content'>" + enrolldata[0].content + "</td>" +
                    "<td class='col-xs-2' id='list_s' width='70'>" + enrolldata[0].create_date + "</td>" +
                    "<td><button class= 'fa fa-trash btn' id='deleteComment" + enrolldata[0].cnum + "'onclick='deleteComment(" + enrolldata[0].cnum + ")'</td>" +
                    "</tr>");

                if (nonComment) {
                    $("#nonComment").remove();
                }
            }
        });
    }

    function deleteComment(cnum) {

        $.ajax({
            type: "POST",
            url: "/group/deleteComment",
            data: {
                'cnum': cnum
            },
            success: function (data) {
                $("#comment" + cnum).remove();
            }
        });
    }

    function deleteBoard(bnum,gnum){

        $.ajax({
            type: "POST",
            url: "/group/deleteBoard",
            data: {
                'bnum': bnum,
                'gnum': gnum
            },
            success: function (data) {

                window.alert("삭제되었습니다.");

                var gnum = JSON.parse(data);

                /*location.href = 'http://localhost/board/boardList/'+gnum;*/ // 로컬에서 실행 할 때 사용할 것

                location.href = 'https://www.dailypartner.xyz/group/boardList/'+gnum;
            }
        });
    }

    $('#enroll').keypress(function (e) {
        if (e.which == 13) {
            $("#enrollBtn").click();

            $("#enroll").val('');
        }
    });
</script>