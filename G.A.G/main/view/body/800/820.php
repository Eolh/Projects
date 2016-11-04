<?php

$category = isset($_REQUEST['category']) ? $_REQUEST['category'] : "noticc";
$board = $category . "board";
$board2 = $board . "_img";
$boardView = isset($_SESSION[$board]) ? $_SESSION[$board] : null;
$boardView2 = isset($_SESSION[$board2]) ? $_SESSION[$board2] : null;
$replyView = isset($_SESSION['replyView']) ? $_SESSION['replyView'] : null;
$updateRnum=isset($_REQUEST['updateRnum'])?$_REQUEST['updateRnum']:null;

?>
<form style="text-align: center;">
    <h1><?= $boardView['m_id'] ?>님의 글</h1>
    <table width="820px" align="center">
        <tr height="10">
            <td width="100">글번호</td>
            <td><?= $boardView['b_num'] ?></td>
            <td width="50"><h5>조회수</h5></td>
            <td width="80"><h5><?= $boardView['b_hit'] ?></h5></td>
        </tr>
        <tr height="20">
            <td>글쓴이</td>
            <td colspan="3"><?= $boardView['m_id'] ?></td>
        </tr>
        <tr height="100">
            <td>제목</td>
            <td colspan="3"><?= $boardView['b_subject'] ?></td>
        </tr>
        <tr height="30">
            <td colspan="4">내용</td>
        </tr>
        <tr height="500">
            <td colspan="4">
                <pre><?= $boardView['b_content'] ?></pre>
                <?php
                if ($boardView2 != 0) {
                    for ($i = 0; $i < count($boardView2); $i++) {
                        $filename = $boardView2[$i]['savefile'];
                        ?>
                        <img src='../../img/board/<?= $filename ?>' width='350px' height='350px'>
                        <?php
                    }
                }
                ?>

            </td>
        </tr>
    </table>
</form>

<?php if (@$_SESSION['loginID'] == $boardView['m_id'] || @$_SESSION['userLevel'] == 999) { ?>
    <div align="center">
        <table>
            <tr>
                <form action="../controller/MainCTL.php?action=818&category=<?=$category?>&bnum=<?= $boardView['b_num']?>" method="post">
                    <input type="submit" value="수정">
                </form>
                <form action="../controller/MainCTL.php?action=812&category=<?=$category?>&bnum=<?= $boardView['b_num'] ?>" method="post">
                    <input type="submit" value="삭제">
                </form>
                <form action="../controller/MainCTL.php?action=810&category=<?=$category?>&point=<?= $boardView['b_num'] ?>" method="post">
                    <input type="submit" value="답변달기">
                </form>
                <form action="../controller/MainCTL.php?action=800&category=<?=$category?>&bnum=<?= $boardView['b_num'] ?>" method="post">
                    <input type="submit" value="이전목록">
                </form>

            </tr>
            <tr><p><br></p></tr>
        </table>
    </div>
<?php } elseif(@$_SESSION['loginID']){ ?>
    <div align="center">
        <table>
            <tr>
                <form action="../controller/MainCTL.php?action=810&category=<?=$category?>&point=<?= $boardView['b_num'] ?>" method="post">
                    <input type="submit" value="답변달기">
                </form>
                <form action="../controller/MainCTL.php?action=800&category=<?=$category?>&bnum=<?= $boardView['b_num'] ?>" method="post">
                    <input type="submit" value="이전목록">
                </form>
            </tr>
        </table>
    </div>
<?php } ?>

<?php if (@$_SESSION['loginID']) { ?>
    <div align="center">
        <table>
            <tr>
                <form action="../controller/MainCTL.php" class="form-inline" role="form">
                    <table>
                        <tr>
                            <td colspan="2">
                                <input type="text" name="m_id" class="form-control"
                                       value="<?= $_SESSION['loginID'] ?>" readonly>

                                <textarea name="content" class="control" cols="150" rows="6"
                                          placeholder="댓글입력"></textarea>
                            </td>
                            <td>
                                <input type="hidden" name="action" value="815">
                                <input type="hidden" name="category" value="<?= $category ?>">
                                <input type="hidden" name="bnum" value="<?= $boardView['b_num'] ?>">
                                <button type="submit">댓글달기</button>

                            </td>
                        </tr>

                    </table>
                </form>
            </tr>
        </table>
    </div>
<?php } else { ?>
    <div align="center">
        <table>
            <tr>
                <form action="../controller/MainCTL.php" class="form-inline" role="form">
                    <table>
                        <tr>
                            <td colspan="2">
                                <input type="text" name="member_id" class="form-control"
                                       value="<?= @$_SESSION['loginID'] ?>" readonly>

                                <textarea name="contents" class="control" cols="150" rows="6"
                                          placeholder="로그인이 필요합니다." disabled='disabled'></textarea>
                            </td>
                        </tr>
                    </table>
                </form>
            </tr>
        </table>
    </div>
<?php } ?>

<?php
if ($replyView) {
    include_once "./body/common/reply.php";
    reply($replyView,$category,$updateRnum);
}

?>
