<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-12-13
 * Time: 오후 4:35
 */

?>
<?php
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 800;
$category = isset($_REQUEST['category']) ? $_REQUEST['category'] : "noticc";
$boardList = isset($_SESSION['boardList']) ? $_SESSION['boardList'] : null;
$boardPageInfo = isset($_SESSION['bpageInfo' . strval($category)]) ? $_SESSION['bpageInfo' . strval($category)] : null;

?>
<center>
    <h1><?=$category?></h1>
    <table width="800px" align="center">
        <tr align="center">
            <td>글번호</td>
            <td>제목</td>
            <td>글쓴이</td>
            <td colspan="2">날짜</td>
            <td>조회수</td>
        </tr>
        <?php
        if ($boardList) {
            for ($iCount = 0; $iCount < count($boardList); $iCount++) {
                echo "<tr align='center'>";

                echo "<td>" . $boardList[$iCount]['b_num'] . "</td>"; //글번호


                echo "<td width='20%' align='left'><a href='../controller/MainCTL.php?action=820&category={$category}&bnum={$boardList[$iCount]['b_num']}'>";
                $temp = "";
                for($y=0 ; $y<intval($boardList[$iCount]['b_depth']) ; $y++)
                {
                    $temp .= "&nbsp&nbsp&nbsp";

                }
                for($y=0 ; $y<intval($boardList[$iCount]['b_depth']) ; $y++)
                {
                    $temp .= "[re]";

                }
                echo $temp.$boardList[$iCount]['b_subject'];
                    echo "</a></td>"; //제목
                echo "<td>" . $boardList[$iCount]['m_id'] . "</td>"; //아이디
                echo "<td colspan='2'>" . $boardList[$iCount]['b_date'] . "</td>"; //날짜
                echo "<td>" . $boardList[$iCount]['b_hit'] . "</td>"; //조회수
                echo "</tr>";
            }
        } else {
            echo "<td colspan='6'> 데이터가 없습니다.</td>";
        }
        ?>
    </table>
    <table>
        <tr>
            <form action="../controller/MainCTL.php" class="form-inline" role="form">
                <input type="text" name="search">
                <input type='hidden' name='action' value='610'/>
                <button type="submit" class="btn btn-default">검색</button>
            </form>

            <?php
            if (@$_SESSION['userLevel'] == 999 && $category == "noticc") { ?>

            <form action="../controller/MainCTL.php" class="form-inline" role="form">
                <input type='hidden' name='action' value='810'/>
                <input type='hidden' name='category' value='<?= $category ?>'/>
                <button type="submit" class="btn btn-default" style="margin-left: 20px">글쓰기</button>
            </form>
        </tr>
    </table>
    <?php
    } elseif (@$_SESSION['loginID']&&$category!="noticc") { ?>

    <form action="../controller/MainCTL.php" class="form-inline" role="form">
        <input type='hidden' name='action' value='810'/>
        <input type='hidden' name='category' value='<?= $category ?>'/>
        <button type="submit" class="btn btn-default" style="margin-left: 20px">글쓰기</button>
    </form>
</tr>
</table>
<?php
}
    if ($boardList) {
        $pageParaName = "bpageNum" . strval($category);
        include_once "./body/common/pageNavi.php";
        @pageNavigate($boardPageInfo, $action, $pageParaName,$category);
    }

    ?>
</center>