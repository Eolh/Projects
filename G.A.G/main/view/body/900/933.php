<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-11-23
 * Time: 오후 8:13
 */
$productData = isset($_SESSION["productData"]) ? $_SESSION["productData"] : null;
$productData2 = isset($_SESSION["productData2"]) ? $_SESSION["productData2"] : null;

if (!$productData) {
    echo "<h2>수정할 데이터가 없습니다.</h2>";
} else {
    ?>
    <form action="../controller/mainCTL.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="936">
        <input type="hidden" name="p_code" value="<?= $productData['p_code'] ?>">


        <div>
            <table>
                <tr width="550px">
                    <td>
                        <ul>
                            <li>
                                <?php if ($productData['p_simage']) { ?>
                                    <img src="../../img/product_s/<?= $productData['p_simage'] ?>" width="300"
                                         height="200">
                                    <input type="hidden" name="p_simage" value="<?= $productData['p_simage'] ?>">
                                <?php } else { ?>
                                    <img src="../../img/static_img/NOIMG.jpg">
                                <?php } ?>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <ul>
                            <li>순번</li>
                            <li><input type="text" readonly="true" name="p_num"
                                       value="<?= $productData['p_num'] ?>"</li>
                            <li>카테고리</li>
                            <li><input type="text" name="p_category"
                                       value="<?= $productData['p_category'] ?>"</li>
                            <li>제품명</li>
                            <li><input type="text" name="p_name" value="<?= $productData['p_name'] ?>"</li>
                            <li>재고량</li>
                            <li><input type="text" name="p_stock" value="<?= $productData['p_stock'] ?>"</li>
                            <li>가격</li>
                            <li><input type="text" name="p_price" value="<?= $productData['p_price'] ?>"</li>

                        </ul>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <h2>삭제할 이미지</h2>
                        <?php
                        for ($i = 0; $i < count($productData2); $i++) {
                            $filename = $productData2[$i]['savefile'];

                            ?>
                            <div >
                                <img src='../../img/product/<?= $filename ?>' width='650px' height='200px'>
                                <input type="checkbox" value="<?= $filename ?>" name="p_files[]">
                            </div>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <h2>이미지 추가</h2>
                        <input type="file" name="refiles[]" multiple=""/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        상세설명 &nbsp;
                        <textarea style="width:800px; height: 220px;"
                                  name="p_content"><?= $productData['p_content'] ?></textarea>

                    </td>
                </tr>
                <tr align="center">
                    <td colspan="2"><input type="button" value="취소" onclick="location=history.back()">

                        <input type="submit" value="수정"></td>
                </tr>
            </table>
        </div>
    </form>
<?php } ?>


