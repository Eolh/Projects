<!DOCTYPE html>
<html>
<head>
    <!-- start: PAGE SETTINGS -->
    <title>balancemyschedule</title>
    <!-- start: META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- end:   META -->
    <!-- end:   PAGE SETTINGS -->

    <!-- start: Material Design fonts -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,600' rel='stylesheet' type='text/css'>

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- end:   font -->

    <!-- start: CSS -->
    <link rel="stylesheet" href="/public/css/login_header.css">
    <link rel="stylesheet" href="/public/css/login_main.css">
    <link rel="stylesheet" href="/public/export_modules/bootstrap/css/bootstrap.min.css">
    <!-- Bootstrap Material Design -->
    <link rel="stylesheet" type="text/css" href="/public/export_modules/dist/css/bootstrap-material-design.css">
    <link rel="stylesheet" type="text/css" href="/public/export_modules/dist/css/ripples.min.css">
    <!-- end:   CSS -->

    <!-- start: script -->
    <script src="/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="/public/export_modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="/public/export_modules/dist/js/material.min.js"></script>
    <script src="/public/export_modules/dist/js/ripples.min.js"></script>
    <script src="/public/js/jquery.FadeWideBgImg.js"></script>
    <!-- end:   script -->

    <script>
        $(document).ready(function () {

            $.material.init();

            $(window).resize(function () {
                var height = $('.navbar').css('height');
                $('main').css('marginTop', height);
            });
        });

        (function ($) {
            jQuery(document).ready(function () {
                $('.slideshow').FadeWideBgImg({interval: 2000});
            });
        }(window.jQuery, window));

    </script>
    <meta name="google-site-verification" content="3rrSoWvwzrmzeO9jyuf8YvvXmL53jWXAKFiLJ36wXE0" />
</head>
<body>
<?php require_once "application/views/common/header_login.php"; ?>

<main>
    <ul class="slideshow">
        <li><img src="/public/img/sample-todo.jpg" alt="슬라이드 이미지" id="bgimg"/></li>
        <li><img src="/public/img/sample-basic.jpg" alt="슬라이드 이미지" id="bgimg"/></li>
        <li><img src="/public/img/sample-weather.jpg" alt="슬라이드 이미지" id="bgimg"/></li>
        <li><img src="/public/img/sample-map.jpg" alt="슬라이드 이미지" id="bgimg"/></li>
    </ul>
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-6" id="loginbox">
                <div class="well bs-component">
                    <form name="frm" class="form-horizontal">
                        <fieldset>
                            <legend><i class="fa fa-calendar-o"></i> Balance</legend>
                            <!--id-->
                            <div class="form-group">
                                <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-star" aria-hidden="true"></i></span>
                                    <input type="text" id="loginID" name="userID" class="form-control input-lg"
                                           placeholder="ID">
                                </div>
                            </div>
                            <!--pw-->
                            <div class="form-group">
                                <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-unlock-alt" aria-hidden="true"></i></span>
                                    <input type="password" id="loginPW" name="userPW" class="form-control input-lg"
                                           placeholder="PASSWORD">
                                </div>
                            </div>
                            <!--sumit-->
                        <span class="input-group-btn">
                            <button class="btn btn-raised btn-primary" onclick="checkUserInfo()" type="button">Check
                            </button>
                        </span>

                            <p id="findAccount" class="form-footer"><i class="fa fa-key" aria-hidden="true"></i> ID or
                                Password find
                            </p>

                        </fieldset>
                    </form>
                </div>
            </div>
        </div>

        <!--error-modal-->
        <div id="login-error" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <p>아이디 혹은 패스워드가 일치하지 않습니다.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

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
