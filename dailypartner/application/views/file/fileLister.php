<?php

for ($x = 0; $x<count($fileList); $x++)
{ ?>
    <tr class="row">
        <td class="col-sm-1 text-center"><?= $fileList[ $x ]->FNum; ?></td>
        <td class="col-sm-3" title="<?= $fileList[ $x ]->Flink; ?>">
            <a href="/public/file/<?= $_SESSION[ 'login' ][ 'ID' ] ?>/<?= $fileList[ $x ]->FName ?>"
                download="<?= $fileList[ $x ]->Flink; ?>" style="word-break: break-all">
                <?php
                $filename = mb_strimwidth($fileList[ $x ]->Flink, '0', '25', '...', 'utf-8');
                echo $filename;
                ?>
            </a>
            <!-- download : as -->
        </td>
        <td class="col-sm-1"><?= $fileList[ $x ]->size; ?></td>
        <td class="col-sm-2">
            <?= ($fileList[ $x ]->hashtag != null) ? $tagConverter->convertFileTags($fileList[ $x ]->hashtag) : "없음!!"; ?>
            <!-- link의 필요성... -->
        </td>
        <td class="col-sm-3"><?= $fileList[ $x ]->date; ?></td>
        <td class="col-sm-2" style="cursor: pointer"
            onclick="schedule(<?= htmlspecialchars(json_encode($fileList[ $x ])) ?>)" title="<?= $fileList[ $x ]->title; ?>">
            <em style="word-break: break-all">
                <?php
                $scheduletitle = mb_strimwidth($fileList[ $x ]->title, '0', '10', '...', 'utf-8');
                echo $scheduletitle;
                ?>
            </em>
        </td>
    </tr>
<?php } ?>
<?php if(empty($fileList) || count($fileList) == 0) { ?>

    <tr class="row">
        <td class="col-sm-1 text-center">なし</td>
        <td class="col-sm-3">NOファイル</td>
        <td class="col-sm-1">なし</td>
        <td class="col-sm-2">なし</td>
        <td class="col-sm-3">なし</td>
        <td class="col-sm-2" style="cursor: pointer">
            <em style="word-break: break-all">
                なし
            </em>
        </td>
    </tr>

<?php } ?>
