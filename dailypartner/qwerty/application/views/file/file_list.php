<!DOCTYPE html>
<html>

<head>

    <!-- start: PAGE SETTINGS -->
    <title></title>
    <meta charset="utf-8">
    <!-- end:   PAGE SETTINGS -->

    <!-- start: META -->
    <!-- end:   META -->

    <!-- start: CSS -->
    <!--
    <link rel="stylesheet" href="http://localhost:8088/public/">
    -->
    <link rel="stylesheet" href="http://localhost:8088/public/export_modules/bootstrap/css/bootstrap.min.css">
    <style>
        body
        {
            width      : 1800px;
            min-height : 800px;
            border     : 1px dashed black;
        }

        .file_list .tagList
        {
            width : 20%;
            float : left;
        }

        .file_list .selectedList
        {
            width : 80%;
            float : left;
        }

        .file_list .filePreviewer
        {
            width : 40%;
            float : left;
        }

        .filePreviewer
        {
            height : 588px;
        }
    </style>
    <!-- end:   CSS -->

    <!-- start: font -->
    <!-- end:   font -->

    <!-- start: script -->
    <!--
    <script src="http://localhost:8088/public/export_modules/"></script>
    -->
    <script src="http://localhost:8088/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="http://localhost:8088/public/export_modules/bootstrap/js/bootstrap.min.js"></script>
    <script>
        function schedule(data) {
            $("#schedule").modal("show");

            var test = data;
            console.log(test);
            $("#title").text(test.title);
            $("#staTime").text(test.startTime);
            $("#endTime").text(test.lastTime);
            $("#content").text(test.content);
        }
    </script>
    <!-- end:   script -->

</head>
<body>
<header>
    <?php require_once "application/views/common/header_main.php"; ?>
</header>
<br> <!-- 나중에 알아서 css로 맞추고 지울 것 -->

<main>

    <div>
        <h1>to-do list</h1>
        <h3>
            <p>1. 링크 제대로 걸기 clear</p>
            <p>2. 일정이랑 연동하기</p>
            <p>3. 파일 유형별로 보이기</p>
       </h3>
    </div>

    <div class="file_list">

        <div class="tagList">
            <ul class="list-group">
                <li class="list-group-item list-group-item-info">
                    TAG LIST
                </li>

                <li class="list-group-item">
                    SELECTED TAG:
                    <a class="btn btn-link" href="/file/reIndex/<?=$selectedName?>">
                        <?=($selectedName != null) ? $selectedName : "default" ?>
                    </a>
                </li>

                <li class="list-group-item">
                    <a class="btn btn-link" href="/file/reIndex/">
                        default
                    </a>
                    <span class="badge">
                        총태그 수 : <?=count($tagList);?>
                    </span>
                    <span class="badge">
                        <!--총파일 수 : --><?/*=count($fileList);*/?>
                        총파일 수 : <?=$cntFiles;?>
                    </span>
                </li>

                <?php

                foreach($tagList as $tag) { ?>

                    <li class="list-group-item">
                        <a class="btn btn-link" href="/file/reIndex/<?=$tag->tagName;?>"><?=$tag->tagName;?></a>
                        <span class="badge">
                            <?=$tag->fileList->cnt;?>
                        </span>
                    </li>

                <?php } ?>

            </ul>
        </div>

        <div class="selectedList">
            <ul class="list-group text-center">
                <li class="list-group-item list-group-item-info">
                    TAG LIST
                </li>

                <li class="list-group-item col-sm-1">
                    NUM
                </li>
                <li class="list-group-item col-sm-3">
                    NAME
                </li>
                <li class="list-group-item col-sm-1">
                    SIZE
                </li>
                <li class="list-group-item col-sm-1">
                    TYPE
                </li>
                <li class="list-group-item col-sm-2">
                    TAG
                </li>
                <li class="list-group-item col-sm-2">
                    DATE
                </li>
                <li class="list-group-item col-sm-2">
                    SCHEDULE
                </li>

                <?php

                for($x=0 ; $x<count($fileList) ; $x++)
                { ?>
                    <li class="list-group-item col-sm-1">
                        <?=$fileList[$x]->FNum;?>
                    </li>
                    <li class="list-group-item col-sm-3">
                        <a href="http://localhost:8088/public/file/<?=$_SESSION['login']['ID']?>/<?=$fileList[$x]->FName?>" download="<?=$fileList[$x]->Flink;?>">
                            <?=$fileList[$x]->Flink;?>
                        </a>
                        <!-- download : as -->
                    </li>
                    <li class="list-group-item col-sm-1">
                        <?=$fileList[$x]->size;?>
                    </li>
                    <li class="list-group-item col-sm-1">
                        <?=$fileList[$x]->type;?>
                    </li>
                    <li class="list-group-item col-sm-2">
                        <?=
                        ($fileList[$x]->hashtag != null) ? $tagConverter->convertFileTags($fileList[$x]->hashtag) : "없음!!" ;
                        ?>
                        <!-- link의 필요성... -->
                    </li>
                    <li class="list-group-item col-sm-2">
                        <?=$fileList[$x]->date;?>
                    </li>
                    <li class="list-group-item col-sm-2">
                        <em onclick="schedule(<?= htmlspecialchars(json_encode($fileList[$x]))?>)">
                            <?=$fileList[$x]->title;?>
                        </em>
                    </li>
                <?php }
                ?>
            </ul>
        </div>
    </div>

    <?php require_once "application/views/file/file_schedule.php"; ?>

</main>
</body>

</html>