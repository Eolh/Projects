<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-12-13
 * Time: 오전 1:52
 */
$productData = $_SESSION['product'];
$productData2 = isset($_SESSION['product2']) ? $_SESSION['product2'] : null;

if ($action % 100 == 10) {

    if (!$productData) {
        echo "<h2> 데이터가 없습니다.</h2>";
    } else {
        ?>
        <form action="../controller/mainCTL.php">
            <input type="hidden" name="action" value="934">

            <div>
                <table width="800px">
                    <tr width="550px">
                        <td>
                            <ul>
                                <li>
                                    <?php if ($productData['p_simage']) { ?>
                                        <img src="../../img/product_s/<?= $productData['p_simage'] ?>" width="300"
                                             height="200">
                                    <?php } else { ?>
                                        <img src="../../img/static_img/NOIMG.jpg">
                                    <?php } ?>
                                </li>
                            </ul>
                        </td>
                        <td>
                            <ul>
                                <li>제품명</li>
                                <li><h2><?= $productData['p_name'] ?></h2></li>
                                <li>가격</li>
                                <li><h4><?= $productData['p_price'] ?>원</h4></li>
                                <li>수량</li>
                                <li><h4><?= $productData['p_stock'] ?>개</h4></li>

                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php
                            if ($productData2 != 0) {
                                for ($i = 0; $i < count($productData2); $i++) {
                                    $filename = $productData2[$i]['savefile'];
                                    ?>
                                    <img src='../../img/product/<?= $filename ?>' width='800px' height='400px'>
                                    <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <tr align="center">
                        <td colspan="2">
                            <pre><?= $productData['p_content'] ?></pre>

                        </td>
                    </tr>
                </table>
            </div>
        </form>
    <?php }


}
?>


