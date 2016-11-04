<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-12-13
 * Time: 오후 10:03
 */
function reply($replyView, $category, $updateRnum)
{
    ?>
    <table width="100%">
        <?php
        if (count($replyView) != 0) {
            for ($iCount = 0; $iCount < count($replyView); $iCount++) {
                echo "<div style='margin-left: 50px; margin-bottom: 10px; width: 1000px'>"; ?>
                <tr>
                    <div>
                    <?php
                    if($_SESSION['loginID'] == $replyView[$iCount]['m_id']&&$replyView[$iCount]['r_num']==$updateRnum){

                        echo "<form action='../controller/MainCTL.php?action=818&category={$category}&bnum={$replyView[$iCount]['p_num']}&r_num={$replyView[$iCount]['r_num']}' method='post'>";
                        echo "<td width='15%'>{$replyView[$iCount]['m_id']}</td>";
                        echo "<td width='70%'><textarea name='content' style='width: 100%'>{$replyView[$iCount]['r_content']}</textarea></td>";
                        echo "<td><input type=submit value='수정' >";
                        echo "<input type=reset value='초기화' ></td>";

                        echo "</form>";

                    }else{
                    ?>

                        <td width="15%"><?= $replyView[$iCount]['m_id'] ?>&nbsp;&nbsp;</td>
                        <td><p><?= $replyView[$iCount]['r_content'] ?></p></td>
                        <td width="3%"><h5><?=$replyView[$iCount]['r_date']?></h5>
                        <?php
                            if ($_SESSION['loginID'] == $replyView[$iCount]['m_id']) {

                                echo "<form action='../controller/MainCTL.php?action=816&category={$category}&bnum={$replyView[$iCount]['p_num']}&r_num={$replyView[$iCount]['r_num']}' style='float:right' method='post'>";
                                echo "<input type=submit value='삭제' >";
                                echo "</form>";

                                echo "<form action='../controller/MainCTL.php?action=820&category={$category}&bnum={$replyView[$iCount]['p_num']}&updateRnum={$replyView[$iCount]['r_num']}' style='float: right' method='post'>";
                                echo "<input type=submit value='수정' >";
                                echo "</form>";


                            }
                            }?>
                            </td>
                    </div>
                </tr>


                </div>
    <?php
            }
        } ?>
    </table>
    <?php
}

?>

