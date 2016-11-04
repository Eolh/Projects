<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-11-06
 * Time: 오후 2:13
 */
include_once 'conf.php';

function insertProduct($data)    //제품 등록
{
    connect_db();
    $sql = "insert into product(p_category,p_code,p_name,p_price,p_stock,p_content) ";
    $sql .= "values('{$data['p_category']}','{$data['p_code']}','{$data['p_name']}','{$data['p_price']}','{$data['p_stock']}','{$data['p_content']}')";
    mysql_query($sql);

    $getPnum = mysql_insert_id();

    return $getPnum;

    mysql_close();
}

function getAllProductCnt()     //전체 제품수
{
    connect_db();
    $sql = "select count(*) from product";
    $result = mysql_query($sql);
    $count = mysql_result($result, 0, 0);
    mysql_close();
    return $count;
}

function getProductCountWithPcategory($category)    //카테고리별 제품수
{

    connect_db();
    $sql = "select count(*) from product where p_category like '$category'";
    $result = mysql_query($sql);
    $cnt = mysql_result($result, 0, 0);
    mysql_close();
    return $cnt;

}

function selectProductListWithPageInfo($PageInfo) //제품리스트 출력
{
    connect_db();
    $CLPP = isset($pageInfo['CLPP']) ? $pageInfo['CLPP'] : 10;
    $limitStart = ($PageInfo['currentPageNum'] - 1) * $CLPP;

    $sql = "SELECT * FROM product ORDER BY p_num DESC limit " . strval($limitStart) . "," . strval($CLPP);
    $result = mysql_query($sql);

    $cnt = 0;
    while ($row = mysql_fetch_array($result)) {
        $productList[$cnt]['p_num'] = $row['p_num'];
        $productList[$cnt]['p_category'] = $row['p_category'];
        $productList[$cnt]['p_code'] = $row['p_code'];
        $productList[$cnt]['p_name'] = $row['p_name'];
        $productList[$cnt]['p_content'] = $row['p_content'];
        $productList[$cnt]['p_stock'] = $row['p_stock'];
        $productList[$cnt]['p_price'] = $row['p_price'];
        $productList[$cnt]['p_simage'] = $row['p_simage'];

        $cnt++;
    }

    mysql_close();
    return $productList;
}

function deleteProduct($p_num)  //제품 삭제
{
    connect_db();
    $sql = "delete from product where p_num = $p_num";
    mysql_query($sql);

    mysql_close();
}

function selectProductbyNum($pnum)      //한 제품의 대한 값을 받을때
{
    connect_db();
    $sql = "SELECT * FROM product WHERE p_num = " . strval($pnum);
    $result = mysql_query($sql);

    if ($result) {
        $row = mysql_fetch_array($result);
        $product['p_num'] = $row['p_num'];
        $product['p_code'] = $row['p_code'];
        $product['p_category'] = $row['p_category'];
        $product['p_content'] = $row['p_content'];
        $product['p_name'] = $row['p_name'];
        $product['p_stock'] = $row['p_stock'];
        $product['p_price'] = $row['p_price'];
        $product['p_simage'] = $row['p_simage'];
    } else {
        $product = null;
    }

    mysql_close();
    return $product;
}

function updateProductByNum($data)      //상품 수정
{
    connect_db();
    $sql = "Update product set p_category='{$data['p_category']}', p_name='{$data['p_name']}', p_content='{$data['p_content']}',p_code='{$data['p_code']}', p_stock='{$data['p_stock']}', p_price='{$data['p_price']}'";
    if (isset($data['p_simage']))
        $sql .= ", p_simage='{$data['p_simage']}'";
    $sql .= " where p_num like'{$data['p_num']}'";
    $result = mysql_query($sql);

    mysql_close();
    return $result;
}

function selectProductListWithPageInfoAndPcategory($PageInfo, $pcategory)  //카테고리별 제품리스트 출력
{
    connect_db();
    $CLPP = isset($PageInfo['CLPP']) ? $PageInfo['CLPP'] : 10;
    $limitStart = ($PageInfo['currentPageNum'] - 1) * $CLPP;

    $sql = "SELECT * FROM product WHERE p_category = '" . strval($pcategory) . "' order by p_num desc limit $limitStart,$CLPP";
    $result = mysql_query($sql);
    $cnt = 0;
    while ($row = mysql_fetch_array($result)) {

        $product[$cnt]['p_num'] = $row['p_num'];
        $product[$cnt]['p_simage'] = $row['p_simage'];

        $product[$cnt]['p_content'] = $row['p_content'];
        $product[$cnt]['p_price'] = $row['p_price'];
        $cnt++;
    }

    mysql_close();
    return $product;
}

function selectimgbyNum($pnum, $table)
{
    connect_db();
    $sql = "select * from $table where p_num ='$pnum'";
    $result = mysql_query($sql);
    $cnt = 0;
    while ($row = mysql_fetch_array($result)) {

        $product2[$cnt]['p_num'] = $row['p_num'];

        $product2[$cnt]['savefile'] = $row['savefile'];
        $product2[$cnt]['filetype'] = $row['filetype'];
        $product2_type = explode('/', $row['filetype']);
        if ($product2_type[1] == 'jpeg')
            $product2_type[1] = 'jpg';
        $cnt++;
    }

    mysql_close();
    return $product2;
}

function checkThumbnail($pnum)
{
    connect_db();
    $sql = "select p_simage from product where p_num = '{$pnum}'";
    $result = mysql_query($sql);
    if ($result) {
        $row = mysql_fetch_array($result);
        $p_simage = $row['p_simage'];
    }

    mysql_close();
    return $p_simage;
}

function deleteImgboard($pnum, $table)
{
    connect_db();
    $sql = "delete from $table where p_num = '{$pnum}'";
    $result = mysql_query($sql);

    mysql_close();
    return $result;
}

function deleteSelectImgboard($saveFileName, $table)
{
    connect_db();
    $sql = "delete from $table where savefile = '{$saveFileName}'";
    $result = mysql_query($sql);

    mysql_close();
    return $result;
}

?>