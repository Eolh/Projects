<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-12-03
 * Time: 오전 10:11
 */
$category=isset($_REQUEST['category'])?$_REQUEST['category']:"noticc";
?>

<H1>글 쓰기</H1>
<hr>
<form action="../controller/MainCTL.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="811">
    <input type="hidden" name="m_id" value="<?= $loginID ?>">
    <input type="hidden" name="category" value="<?= $category ?>">
    <table width="93%" height="250px">
        <tr>
            <td width="275px">
                <h3 align="center"></h3>
                <table height="100%" align="center">
                    <tr>
                        <td>
                            글 제목 : <input type="text" name="b_subject">
                        </td>
                    </tr>
                    <tr>
                        <td height="100px">
                            이미지 추가:
                            <input type="file" name="b_files[]" multiple/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan=>
                내용
                <textarea style="width:820px; height: 220px;" name="b_content"></textarea>
            </td>
        </tr>
        <tr>
            <?php if(!empty($_REQUEST['point']))
            {
                echo "<input type='hidden' name='point' value='{$_REQUEST['point']}'>";
                echo '<input type="hidden" name="action" value="819">';
            }
            ?>
            <td colspan="2">
                <input type="submit" value="작성">
                <input type="reset" value="초기화">
                <input type="button" value="취소" onclick="location=history.back()">
            </td>
        </tr>
    </table>
</form>