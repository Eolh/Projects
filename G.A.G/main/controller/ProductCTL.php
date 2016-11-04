<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-11-06
 * Time: 오후 3:26
 */

include_once "../model/productDAO.php";

function ProductController($action)     //제품 관련 컨트롤
{
    switch ($action % 100) {
        case 0:
            $category = $_SESSION['pcategory'];
            $ClppandCppb = array(9, 10);

            $actionIndex = floor($action / 100); // 액션코드에 따른 메뉴 인덱스 계산

            $pageParaName = "ppageNum" . strval($action);
            $pageInfoName = "pageInfo" . strval($action);
            $pageInfo = isset($_SESSION[$pageInfoName]) ? $_SESSION[$pageInfoName] : null;

            $pcategory = $category[$actionIndex - 1];

            if (!$pageInfo) // 기존 페이지정보 객체가 없다면
                $ppageNum = isset($_REQUEST[$pageParaName]) ? $_REQUEST[$pageParaName] : 1;
            else
                $ppageNum = isset($_REQUEST[$pageParaName]) ? $_REQUEST[$pageParaName] : 1;

            $cnt = getProductCountWithPcategory($pcategory); // product 테이블의 카테고리 레코드 갯수 조회
            $productPageInfo = getPageInfo($ppageNum, $cnt, $ClppandCppb[0], $ClppandCppb[1]);
            $productList = selectProductListWithPageInfoAndPcategory($productPageInfo, $pcategory);
            $_SESSION[$pageInfoName] = $productPageInfo;
            $_SESSION['productList'] = $productList;

            header("location:../view/MainView.php?action=$action");
            break;
        case 10:
            $action = $_REQUEST['action'];

            $pnum = $_REQUEST['pnum'];
            $product = selectProductbyNum($pnum);
            $product2 = selectimgbyNum($pnum, "product_img");
            $_SESSION['product'] = $product;
            $_SESSION['product2'] = $product2;


            header("location:../view/MainView.php?action=$action&pnum=$pnum");
            break;

    }
}