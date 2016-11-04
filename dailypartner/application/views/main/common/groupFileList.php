<?php

if ( $fileList )
{
    foreach ($fileList as $file)
    { ?>

        <div class="list-group-item">
            <a href="/file/">
                <div class="row-picture">
                    <i class="material-icons">folder</i>
                </div>
            </a>

            <div class="row-content">
                <a href="/public/file/<?= $_SESSION['login']['ID'] ?>/<?=$file->FName?>" download="<?=$file->Flink?>">
                    <!--                    <h4 class="list-group-item-heading">(누르먄 다운로드)-->
                    <!--                        <hr>-->
                    <?=$file->Flink?>
                    <!--                    </h4>-->
                </a>

                <p class="list-group-item-text">
                    <?=$file->name?>
                </p>
            </div>
        </div>

        <div class="list-group-separator"></div>

        <?php
    }
} else { ?>
    <!--    <div>파일이 존재하지 않습니다.<!--파일없으면 여기다가 처리--><!--</div>-->
    <div class="list-group-item">

        <div class="row-content">

            <p class="list-group-item-text">
            <h4>ファイルがない</h4>
            </p>
        </div>
    </div>
<?php }

?>