<!DOCTYPE html>
<html>

<head>

    <!-- start: PAGE SETTINGS -->
    <title>balancemyschedule</title>

    <?php require_once "application/views/common/head_list.php"; ?>

    <!-- start: CSS -->
    <style>

        .main-paper a {
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0;
            color: #27282c;
            text-decoration: none;
        }
        .main-paper a:hover {
            color: #00c5cc;
            background-color: rgba(224, 232, 232, 0.7);
        }

        .table-title {
            text-align: center;
            background: rgba(39, 40, 44, 0.7) linear-gradient(to bottom,
            rgba(224, 232, 232, 0.25),
            rgba(224, 232, 232, 0.25) 45%,
            rgba(39, 40, 44, 0) 45%,
            rgba(39, 40, 44, 0) 100%); /*rgba(39, 40, 44, 0.7), #27282c 50%);*/
            color: #fff;
        }

        .table-title-s1 {
            background: #577cbd linear-gradient(to bottom,
            rgba(224, 232, 232, 0.25),
            rgba(224, 232, 232, 0.25) 45%,
            rgba(39, 40, 44, 0) 45%,
            rgba(39, 40, 44, 0) 100%); /*rgba(39, 40, 44, 0.7), #27282c 50%);*/
        }

    </style>
    <!-- end:   CSS -->

    <!-- start: script -->
    <script>
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
                "<iframe src='https://docs.google.com/gview?url=https://balancemyschedule.tk/public/file/foway/" + test.FName + "&embedded=true' style='width:100%; height:450px;' frameborder='0'>" + "</iframe>"
            );
        }

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <!-- end:   script -->

</head>
<body>
<?php require_once "application/views/common/header_main.php"; ?>

<main>
    <div class="main-paper">
        <div>
            <h3><span class="glyphicon glyphicon-list-alt"></span> to-do list</h3>
            <ol>
                <li> 링크 제대로 걸기 clear</li>
                <li> 일정이랑 연동하기</li>
                <li> 파일 유형별로 보이기</li>
            </ol>
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
                            <a class="btn" href="/file/reIndex/<?= $selectedName ?>" style="margin: 0">
                                <?= ($selectedName != null) ? $selectedName : "ALL" ?>
                            </a>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td style=" background-color: rgba(224, 232, 232, 0.5);">
                            <a href="/file/reIndex/" style="margin: 0;">
                                <p style="padding: 5px 8px; margin: 0;">all
                                    <span class="badge" style="float: right">총태그 수 : <?= count($tagList); ?></span>
                                    <span class="badge" style="float: right">총파일 수 : <?= $cntFiles; ?></span>
                                </p>
                            </a>
                        </td>
                    </tr>
                    <?php
                    foreach ($tagList as $tag) { ?>
                        <tr>
                            <td>
                                <a href="/file/reIndex/<?= $tag->tagName; ?>">
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
                    <tr>
                        <th>NUM</th>
                        <th>NAME [download]</th>
                        <th>SIZE</th>
                        <th>TAG</th>
                        <th>DATE</th>
                        <th>SCHEDULE</th>
                    </tr>
                    </thead>
                    <!--contents list-->
                    <tbody>
                    <?php

                    for ($x = 0; $x < count($fileList); $x++) { ?>
                        <tr>
                            <td><?= $fileList[$x]->FNum; ?></td>
                            <td>
                                <a href="/public/file/<?= $_SESSION['login']['ID'] ?>/<?= $fileList[$x]->FName ?>"
                                   download="<?= $fileList[$x]->Flink; ?>">
                                    <?= $fileList[$x]->Flink; ?>
                                </a>
                                <!-- download : as -->
                            </td>
                            <td><?= $fileList[$x]->size; ?></td>
                            <td>
                                <?=
                                ($fileList[$x]->hashtag != null) ? $tagConverter->convertFileTags($fileList[$x]->hashtag) : "없음!!";
                                ?>
                                <!-- link의 필요성... -->
                            </td>
                            <td><?= $fileList[$x]->date; ?></td>
                            <td style="cursor: pointer" onclick="schedule(<?= htmlspecialchars(json_encode($fileList[$x])) ?>)">
                                <em><?= $fileList[$x]->title; ?></em>
                            </td>
                        </tr>
                    <?php } ?>
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