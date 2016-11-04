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
<body>
<?php require_once "application/views/common/header_main.php"; ?>

<!--게시글 리스트-->
<main class="main-paper" style="margin: 50px">
    <table class="list_table" cellpadding="0" cellspacing="0" align="center" style="margin: auto">
        <tr id="list_title" align="center">
            <th class="underline" id="list_subject" colspan="2">タイトル</th>
            <th class="underline">writer</th>
            <th class="underline">日付</th>
            <th class="underline">view</th>
        </tr>

        <?php
        if (!$boardList) {
            echo "<tr><td align='center' colspan='5'>掲示物がありません。</td></tr>";
        } else {
            foreach ($boardList as $item) {
                if ($item->all_check == 1) {
                    echo "<tr style='background-color: rgba(0, 197, 204, 0.5); font-weight: bold;'>";
                    echo "<td id = 'list_subject' colspan='2'><a href='/group/boardDetailView/$item->bnum'>$item->title</a></td>";
                    echo "<td id = 'center'>$item->create_by</td>";
                    echo "<td id = 'list_s' width='70'>$item->create_date</td>";
                    echo "<td id = 'list_s'>$item->hit</td>";
                    echo "</tr>";
                } else {
                    echo "<tr>";
                    echo "<td id = 'list_subject' colspan='2'><a href='/group/boardDetailView/$item->bnum'>$item->title</a></td>";
                    echo "<td id = 'center'>$item->create_by</td>";
                    echo "<td id = 'list_s' width='70'>$item->create_date</td>";
                    echo "<td id = 'list_s'>$item->hit</td>";
                    echo "</tr>";
                }
            }
        }
        ?>
        <tr align='right'>
            <td colspan='5'>
                <button class="btn"><a href="/group/boardWriteView/<?=$boardList[0]->gnum ?>">WRITE</a></button>
            </td>
        </tr>
    </table>
    </form>
</main>
</body>
</html>
