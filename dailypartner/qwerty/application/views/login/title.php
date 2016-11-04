<!DOCTYPE html>
<html>

<head>

    <!-- start: PAGE SETTINGS -->
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--initial-scale 속성은 페이지가 처음 로드될 때 줌 레벨을 조정한다. maximum-scale, minimum-scale,
     그리고 user-scalable 속성들은 사용자가 얼마나 페이지를 줌-인, 줌-아우트 할 수 있는지를 조정한다.-->
    <!-- end:   PAGE SETTINGS -->

    <!-- start: META -->
    <!-- end:   META -->

    <!-- start: CSS -->
    <!--<link rel="stylesheet" href="/public/css/">-->
    <link rel="stylesheet" href="/public/css/header_login.css">
    <link rel="stylesheet" href="/public/css/login_main.css">
    <link rel="stylesheet" href="/public/export_modules/bootstrap/css/bootstrap.css">
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
    <script src="/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="https://code.jquery.com/jquery-2.2.3.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="/public/export_modules/bootstrap/js/bootstrap.min.js"></script>
    <!-- end:   script -->
</head>
<body>
<?php require_once "application/views/common/header_login.php"; ?>

<main>
    <!--login-PW error-->
    <div class="modal fade" id="login-error">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></h4>
                </div>
                <div class="modal-body">
                    <p>입력된 값이 없습니다.</p> <!--ID 또는 PASSWARD가 일치하지 않습니다.-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">OK</button><!--Close-->
                                                                                                  <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>

    <!--joinForm-->
    <div id="joinForm" class="form">
        <p class="form-title"><i class="fa fa-calendar-o fa-3x"></i></p>
        <div class="form-content">
            <form name="frm">
                <ul>
                    <li>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <input type="text" id="loginID" name="userID" placeholder="ID" autofocus>
                    </li>
                    <li>
                        <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                        <input type="password" id="loginPW" name="userPW" placeholder="PASSWORD">
                    </li>
                    <!--onblur : 사용자가 필드를 떠날 때 js실행-->
                    <li>
                        <button class='btn' onclick="checkUserInfo()" type="button">Check</button>
                    </li>
                </ul>
            </form>
        </div>
        <p id="findAccount" class="form-footer"><i class="fa fa-key" aria-hidden="true"></i> ID or Password find</p>
    </div>
</main>

<script>/*top-menu 높이에따른 변동*/
    $(window).resize(function () {
        var height = $('header').css('height');
        $('main').css('marginTop', height);
    });
</script>
                          <!--값넘기기-->
<script>
    function checkUserInfo() {

        var form = $("form[name='frm']")[0]; //폼태그가리킴
        var formData = new FormData(form); //입력한 값 찾음

        console.log(formData);

        $.ajax({
            type: 'POST',
            url: '/login/user_check',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data == "true") {
                    console.log(data);

                    window.parent.location.href = '/main/';

                } else {
                    $('#login-error').modal('show');//login-error, login-join
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }
</script>
</body>
</html>