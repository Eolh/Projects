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
    <div class="row">
        <!--table list-->
        <div class="col-lg-3 col-sm-3 col-xs-12">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th class="table-title">タグのリスト</th>
                </tr>
                <tr>
                    <th>見ているタグ:
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
                                <span class="badge" style="float: right">ファイルの数:<?= count($tagList); ?></span>
                                <span class="badge" style="float: right">タグの数:<?= $cntFiles; ?></span>
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
                    <th colspan="7" class="table-title table-title-s1">ファイルのリスト</th>
                </tr>
                <tr class="row">
                    <th class="col-sm-1 text-center">番号</th>
                    <th class="col-sm-3">名[ダウンロード]</th>
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

    <!--maybe modal-->
    <?php require_once "application/views/file/file_schedule.php"; ?>
</div>