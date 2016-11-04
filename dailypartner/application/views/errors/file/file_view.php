<div id="FileList">
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

            <div class="col-lg-3 col-sm-3 col-xs-12">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>TAG LIST</th>
                    </tr>
                    <tr>
                        <th>SELECTED TAG:
                            <a class="btn" href="/file/reIndex/<?= $selectedName ?>">
                                <?= ($selectedName != null) ? $selectedName : "default" ?>
                            </a>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <a class="btn" href="/file/reIndex/">
                                default
                            </a>
                            <span class="badge" style="float: right; margin: 5px">총태그 수 : <?= count($tagList); ?></span>
                            <span class="badge" style="float: right; margin: 5px"><!--=count($fileList);-->
                                총파일 수 : <?= $cntFiles; ?>
                            </span>
                        </td>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    foreach ($tagList as $tag) { ?>
                        <a href="/file/reIndex/<?= $tag->tagName; ?>">
                            <tr>
                                <td>
                                    <p style="padding: 5px 8px; margin: 0;"><?= $tag->tagName; ?>
                                        <span class="badge" style="float: right"><?= $tag->fileList->cnt; ?></span></p>
                                </td>
                            </tr>
                        </a>
                    <?php } ?>
                    <tr>
                        <td class="text-center" style="border-bottom: 2px solid #ddd;">more tag...
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-lg-9 col-sm-9 col-xs-12">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th colspan="7" style="text-align: center">FILE LIST</th>
                    </tr>
                    <tr>
                        <th>NUM</th>
                        <th>NAME</th>
                        <th>SIZE</th>
                        <th>TYPE</th>
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
                            <td><?= $fileList[$x]->type; ?></td>
                            <td>
                                <?=
                                ($fileList[$x]->hashtag != null) ? $tagConverter->convertFileTags($fileList[$x]->hashtag) : "없음!!";
                                ?>
                                <!-- link의 필요성... -->
                            </td>
                            <td><?= $fileList[$x]->date; ?></td>
                            <td>
                                <em onclick="schedule(<?= htmlspecialchars(json_encode($fileList[$x])) ?>)">
                                    <?= $fileList[$x]->title; ?>
                                </em>
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
</div>