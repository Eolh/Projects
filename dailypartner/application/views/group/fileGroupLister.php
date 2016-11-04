<?php

for ($x = 0; $x<count($fileGroupList); $x++)
{ ?>
    <tr class="row">
        <td class="col-sm-1 text-center"><?= $fileGroupList[ $x ]->FNum; ?></td>
        <td class="col-sm-3" title="<?= $fileGroupList[ $x ]->Flink; ?>">
            <a href="/public/file/<?= $_SESSION[ 'login' ][ 'ID' ] ?>/<?= $fileGroupList[ $x ]->FName ?>"
                download="<?= $fileGroupList[ $x ]->Flink; ?>" style="word-break: break-all">
                <?php
                $filename = mb_strimwidth($fileGroupList[ $x ]->Flink, '0', '25', '...', 'utf-8');
                echo $filename;
                ?>
            </a>
            <!-- download : as -->
        </td>
        <td class="col-sm-1"><?= $fileGroupList[ $x ]->size; ?></td>
        <td class="col-sm-2">
            <?= ($fileGroupList[ $x ]->hashtag != null) ? $tagConverter->convertFileTags($fileGroupList[ $x ]->hashtag) : "없음!!"; ?>
            <!-- link의 필요성... -->
        </td>
        <td class="col-sm-3"><?= $fileGroupList[ $x ]->date; ?></td>
        <td class="col-sm-2" style="cursor: pointer"
            onclick="group_schedule(<?= htmlspecialchars(json_encode($fileGroupList[ $x ])) ?>)" title="<?= $fileGroupList[ $x ]->title; ?>">
            <em style="word-break: break-all">
                <?php
                $scheduletitle = mb_strimwidth($fileGroupList[ $x ]->title, '0', '10', '...', 'utf-8');
                echo $scheduletitle;
                ?>
            </em>
        </td>
    </tr>
<?php } ?>

<?php
if(empty($fileGroupList) || count($fileGroupList) == 0){ ?>

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
