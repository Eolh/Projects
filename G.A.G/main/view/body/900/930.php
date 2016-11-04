제품정보 리스트

<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-11-06
 * Time: 오후 7:36
 */


$pagenaviInfo = isset($_SESSION['productPageInfo']) ? $_SESSION['productPageInfo'] : null;
$productList = isset($_SESSION['productList']) ? $_SESSION['productList'] : null; ?>

<table border='1' align='center'>
    <tr>
        <th>순번</th>
        <th>카테고리</th>
        <th>코드</th>
        <th>상품명</th>
        <th>재고량</th>
        <th>가격</th>
        <th>썸네일명</th>
        <th>수정</th>
        <th>삭제</th>
    </tr>
    <tr>
        <td colspan='10'><input type='button' value='상품추가' onclick="location='../controller/mainCtl.php?action=931'">
        </td>
    </tr>
<?php
if (!$productList) {
    echo "<tr><td colspan='10'>저장된 데이터가 없습니다</td></tr>";
} else {
    foreach ($productList as $product) {
        echo "<tr>";
        foreach ($product as $myKey => $myValue) {
            if ($myKey != "p_content") {
                echo "<td>";
                echo "$myValue";
                echo "</td>";
            }
        }
        echo "<td><a href='../controller/MainCTL.php?action=934&num={$product['p_num']}'>UPD</a></td>";
        echo "<td><a href='../controller/MainCTL.php?action=935&num={$product['p_num']}&ppageNum={$pagenaviInfo['currentPageNum']}'>DEL</a></td>";
        echo "</tr>";
    }
}
echo "<tr></tr>";
echo "</table>";
if ($productList) {
    include "./body/common/pageNavi.php";
    pageNavigator($pagenaviInfo, $action, "ppageNum");
}
?>