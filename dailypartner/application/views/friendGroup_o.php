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
    <style>
        #dialog label, #dialog input {
            display: block;
        }

        #dialog label {
            margin-top: 0.5em;
        }

        #dialog input, #dialog textarea {
            width: 95%;
        }

        #tabs {
            margin-top: 1em;
        }

        #tabs li .ui-icon-close {
            float: left;
            margin: 0.4em 0.2em 0 0;
            cursor: pointer;
        }

        #add_tab {
            cursor: pointer;
        }
    </style>
    <!--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
-->
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

    <script src="/public/js/command.js">

    </script>
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
    <script>
        $(function () {
            var tabTitle = $("#tab_title"),
                tabContent = $("#tab_content"),
                tabTemplate = "<li><a href='#{href}' class='btn'>#{label}<a><i class='fa fa-times close-icon' aria-hidden='true' role='presentation'></i></a>.</a></li>",
                tabCounter = 2;

            var tabs = $("#tabs").tabs();

            // Modal dialog init: custom buttons and a "close" callback resetting the form inside
            var dialog = $("#dialog").dialog({
                autoOpen: false,
                modal: true,
                buttons: {
                    Add: function () {
                        addTab();
                        $(this).dialog("close");
                    },
                    Cancel: function () {
                        $(this).dialog("close");
                    }
                },
                close: function () {
                    form[0].reset();
                }
            });

            // AddTab form: calls addTab function on submit and closes the dialog
            var form = dialog.find("form").on("submit", function (event) {
                addTab();
                dialog.dialog("close");
                event.preventDefault();
            });

            // Actual addTab function: adds new tab using the input from the form above
            function addTab() {
                var label = tabTitle.val() || "Tab " + tabCounter,
                    id = "tabs-" + tabCounter,
                    li = $(tabTemplate.replace(/#\{href\}/g, "#" + id).replace(/#\{label\}/g, label)),
                    tabContentHtml = tabContent.val() || "Tab " + tabCounter + " content.";

                tabs.find(".ui-tabs-nav").append(li);
                tabs.append("<div id='" + id + "'><p>" + tabContentHtml + "</p></div>");
                tabs.tabs("refresh");
                tabCounter++;
            }

            // AddTab button: just opens the dialog
            $("#add_tab")
                .button()
                .on("click", function () {
                    dialog.dialog("open");
                });

            // Close icon: removing the tab on click
            tabs.on("click", "i.close-icon", function () {
                var panelId = $(this).closest("li").remove().attr("aria-controls");
                $("#" + panelId).remove();
                tabs.tabs("refresh");
            });

            tabs.on("keyup", function (event) {
                if (event.altKey && event.keyCode === $.ui.keyCode.BACKSPACE) {
                    var panelId = tabs.find(".ui-tabs-active").remove().attr("aria-controls");
                    $("#" + panelId).remove();
                    tabs.tabs("refresh");
                }
            });
        });
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
            <ol>
                <li> 검색창에서 이름 혹은 번호를 검색하여 친구 추가</li>
                <li> 친구의 리스트와 그룹의 리스트</li>
                <li> 그룹 추가시, 친구 리스트에서 선택해 그룹원으로 설정</li>
            </ol>
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
                                    <a onclick="friendaddlist_delete('<?= $friend->UID; ?>')" title="<?= $friend->UID; ?>"
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
                                    <img class="circle" src="http://lorempixel.com/56/56/people/1" alt="icon">
                                    <!-- src="" -->
                                </div>
                            </td>
                            <td class="col-lg-8 col-sm-8">
                                <p style="padding: 5px 8px; margin: 0;"><?= $friend->name . " ( " . $friend->ID . " ) "; ?>
                                </p>

                                <p style="padding: 5px 8px; margin: 0;"><i class="fa fa-phone-square"
                                                                           aria-hidden="true"></i>&nbsp;<?= $friend->Tel; ?>

                            </td>
                            <td class="col-lg-3 col-sm-3" style="margin-right: 8px; vertical-align: middle;">
                                <a onclick="group_add('<?= $friend->UID; ?>')" title="<?= $friend->UID; ?>"
                                   class="btn btn-fab" style="height: 40px; min-width: 40px; width: 40px; float:right;"><i
                                        class="material-icons" style="font-weight: bolder">add</i></a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>


            <!--Add Tab 버튼 눌렀을 때 팝업 창[그룹이름 써서 추가]-->
            <div id="dialog" title="Tab data">
                <form>
                    <fieldset class="ui-helper-reset">
                        <label for="tab_title">Title</label>
                        <input type="text" name="tab_title" id="tab_title" value="Tab Title"
                               class="ui-widget-content ui-corner-all">
                        <label for="tab_content">Content</label>
                        <textarea name="tab_content" id="tab_content" class="ui-widget-content ui-corner-all">Tab content</textarea>
                    </fieldset>
                </form>
            </div>

            <!--group list-->
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th class="table-title table-title-s1">GROUP LIST</th>
                    </tr>
                    <tr>
                        <th>
                            jQuery UI tab
                            <!--탭 추가 버튼-->
                            <button id="add_tab">Add Tab</button>
                        </th>
                    </tr>
                    <tr>
                        <th>
                            <!--기본적으로 설정되어있던 탭-->
                            <div id="tabs">
                                <div class="btn-group btn-group-justified btn-group-raised">
                                    <a href="#tabs-1" class="btn ">Left</a>
                                    <a href="javascript:void(0)" class="btn ">Middle</a>
                                    <a href="javascript:void(0)" class="btn ">dfst</a>
                                    <a href="javascript:void(0)" class="btn ">Right</a>
                                </div>
                                <hr>
                                <ul class="btn-group btn-group-justified btn-group-raised">
                                    <li><a href="#tabs-1" class="btn">Nunc tincidunt</a> <span
                                            class="ui-icon ui-icon-close" role="presentation">Remove Tab</span></li>
                                </ul>
                                <div id="tabs-1">
                                    <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec
                                        arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper
                                        ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean
                                        tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis
                                        orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie
                                        erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt
                                        interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
                                </div>
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody id="GroupList">
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</main>
</body>
</html>