<script>

    file_list();

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

</script>

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