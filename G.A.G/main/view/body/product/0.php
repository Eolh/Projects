<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-12-13
 * Time: 오후 4:13
 */
$pageselect = "ppageNum" . $action;
$pageInfo = "pageInfo" . $action;


$pagenaviInfo = isset($_SESSION[$pageInfo]) ? $_SESSION[$pageInfo] : null;
$productList = isset($_SESSION['productList']) ? $_SESSION['productList'] : null;
$cnt = 0;

?>
<table align="center" width="850">
    <?php if (!$productList) {
        echo "<tr><td>값이 없습니다</td></tr></table>";
    } else { ?>
        <?php foreach ($productList as $product) {
            if ($cnt % 3 == 0) {
                echo "<tr>";
            }
            echo "<td>";
            echo "<div id='product'>";
            echo "<ul>";
            foreach ($product as $myKey => $value) {
                if($myKey=="p_num")
                    echo "";
                elseif ($myKey == "p_simage"){
                    ?><li><a href="../controller/MainCTL.php?action=<?=$action+10?>&pnum=<?=$product['p_num'];?>"> <img align='center' src='<?php if($value){echo "../../img/product_s/$value";}else{echo"../../img/product_s/C116362_S.jpg";}?>' width='230'height='370'></a></li><hr>
                <?php } else
                    echo "<li>{$value}</li>";

            }
            echo "</ul>";
            echo "</div>";
            echo "</td>";
            $cnt++;
            if ($cnt % 3 == 0) {
                echo "</tr>";
                $cnt = 0;
            }

        } ?>

        </table>
    <?php }
    if ($productList) {
        include_once './body/common/pageNavi.php';
        pageNavigator($pagenaviInfo, $action, $pageselect);

    }
?>