<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-11-06
 * Time: 오후 3:27
 */
include_once "../model/memberDAO.php";
include_once "../model/productDAO.php";
function adminController($action)
{
    switch ($action) {

        case 910:

            $pageNum = isset($_REQUEST['mpageNum']) ? $_REQUEST['mpageNum'] : 1;
            $cnt = getMemberCount();
            $memberPageInfo = getPageInfo($pageNum, $cnt, 10, 10);
            $memberList = selectMemberListWithPageInfo($memberPageInfo);

            $_SESSION['memberPageInfo'] = $memberPageInfo;
            $_SESSION['memberList'] = $memberList;
            header("location:../view/MainView.php?action=$action");
            break;

        case 914: // 회원데이터 수정 처리
            $data['m_num'] = isset($_REQUEST['m_num']) ? $_REQUEST['m_num'] : null;
            $data['m_id'] = isset($_REQUEST['m_id']) ? $_REQUEST['m_id'] : null;
            $data['m_password'] = isset($_REQUEST['m_password']) ? $_REQUEST['m_password'] : null;
            $data['m_name'] = isset($_REQUEST['m_name']) ? $_REQUEST['m_name'] : null;
            $data['m_tel'] = isset($_REQUEST['m_tel']) ? $_REQUEST['m_tel'] : null;
            $data['m_level'] = isset($_REQUEST['m_level']) ? $_REQUEST['m_level'] : null;

            $result = updateMemberByNum($data);
            if (!$result) {
                $action = 919;
            }
            $action = 910;
            $memberPageInfo = $_SESSION['memberPageInfo'];
            $pageNum = $memberPageInfo['currentPageNum'];
            header("location:./MainCTL.php?action=$action&pageNum=$pageNum");  //콘트롤러 재호출
            break;

        case 912: //수정요구 처리
            $num = $_REQUEST['num'];
            $member = selectMemberByNum($num);
            if (!$member) {
                $action = 919;
            } else {
                $_SESSION['member'] = $member;
                $action = 913;  //수정처리 뷰로 리다이렉트
            }
            header("location:../view/MainView.php?action=$action");
            break;

        case 917: // 삭제요구 처리
            $num = $_REQUEST['num'];
            $result = deleteMemberByNum($num);
            if (!$result) {
                $action = 919;
            } else {
                $action = 910;
            }
            header("location:./MainCTL.php?action=$action&pageNum={$_REQUEST['pageNum']}");  //콘트롤러 재호출
            break;

        case 920:
            header("location:../view/MainView.php?action=$action");
            break;

        case 930:  //상품 리스트보기

            $productPageInfo = $_SESSION['productPageInfo'];

            if (!$productPageInfo) {
                $pageNum = 1;
            } else {
                $pageNum = isset($_REQUEST['ppageNum']) ? $_REQUEST['ppageNum'] : 1;
            }

            $CLPP = 10;
            $CPPB = 10;

            $count = getAllProductCnt();
            $productPageInfo = getPageInfo($pageNum, $count, $CLPP, $CPPB);
            $productList = selectProductListWithPageInfo($productPageInfo);
            $_SESSION['cnt'] = $count;
            $_SESSION['productPageInfo'] = $productPageInfo;
            $_SESSION['productList'] = $productList;
            header("location:../view/MainView.php?action=$action");

            break;

        case 931:
            header("location:../view/MainView.php?action=$action");
            break;

        case 932:
            $productImgSavePath = "../../img/product/";
            $thumbnailImgSavePath = "../../img/product_s/";
            $thumbnailImgHeight = 150; // 썸네일 이미지 높이를 150px로 설정
            $fileMaxSize = 20000000; // 파일 최대 크기 2Mbyte 설정
            $table_name = "product_img";

            $data['p_category'] = isset($_REQUEST['p_category']) ? $_REQUEST['p_category'] : null;
            $data['p_code'] = $data['p_category'];
            $data['p_name'] = isset($_REQUEST['p_name']) ? $_REQUEST['p_name'] : null;
            $data['p_stock'] = isset($_REQUEST['p_stock']) ? $_REQUEST['p_stock'] : null;
            $data['p_price'] = isset($_REQUEST['p_price']) ? $_REQUEST['p_price'] : null;
            $data['p_content'] = isset($_REQUEST['p_content']) ? $_REQUEST['p_content'] : null;
            $data['p_simage'] = null;
            $getPnum = insertProduct($data);
            if ($_FILES['p_simage']) {      //썸네일 파일 등록

                $uploadFile = $_FILES['p_simage'];

                $imgFileType = pathinfo($uploadFile['name'], PATHINFO_EXTENSION); //이미지 파일 확장자 추출
                if ($imgFileType == "jpg" || $imgFileType == "jpeg" || $imgFileType == "png" || $imgFileType == "gif") {
                    $thumbnailFileName = $data['p_category'] . date("YmdHis") . strval($getPnum) . "_S" . "." . strval($imgFileType);
                    $upImgFileInfo['name'] = isset($_FILES['p_simage']['name']) ? $_FILES['p_simage']['name'] : null;
                    $upImgFileInfo['tmp_name'] = isset($_FILES['p_simage']['tmp_name']) ? $_FILES['p_simage']['tmp_name'] : null;
                    $upImgFileInfo['type'] = isset($_FILES['p_simage']['type']) ? $_FILES['p_simage']['type'] : null;
                    $upImgFileInfo['size'] = isset($_FILES['p_simage']['size']) ? $_FILES['p_simage']['size'] : null;
                    $upImgFileInfo['error'] = isset($_FILES['p_simage']['error']) ? $_FILES['p_simage']['error'] : null;

                    singleFileUpload($upImgFileInfo, $thumbnailImgSavePath, $thumbnailFileName, $fileMaxSize);
                    $data['p_simage'] = $thumbnailFileName;
                }
            }

            if ($_FILES['p_files']) {       //상세이미지 등록
                $uploadPFimg = reArrayfiles($_FILES['p_files']);

                $cnt = 1;
                if (isset($uploadPFimg[$cnt]['name'])) {
                    foreach ($uploadPFimg as $uploadFile) {

                        $imgFileType = pathinfo($uploadFile['name'], PATHINFO_EXTENSION); //이미지 파일 확장자 추출
                        $saveFile = $data['p_category'] . date("YmdHis") . strval($getPnum) . strval($cnt);
                        $saveFilename = strval($saveFile) . "." . strval($imgFileType);
                        $cnt++;

                        singleFileUpload($uploadFile, $productImgSavePath, $saveFilename, $fileMaxSize);

                        $data2['p_num'] = $getPnum;
                        $data2['savefile'] = $saveFilename;
                        $data2['filetype'] = $uploadFile['type'];

                        $result = insertMultiFiles($data2, $table_name);
                        if (!$result)
                            exit(var_dump("실패"));
                    }
                    if (!($_FILES['p_simage']['name'])) {              //대표이미지가 없을 경우 썸네일 만들기

                        $thumbnailFileName = $data['p_category'] . date("YmdHis") . strval($getPnum) . "_S" . "." . strval($imgFileType);
                        var_dump($thumbnailFileName);
                        if ($imgFileType == "jpg" || $imgFileType == "jpeg" || $imgFileType == "png" || $imgFileType == "gif") {

                            $src = $productImgSavePath . strval($saveFilename);
                            $dest = $thumbnailImgSavePath . strval($thumbnailFileName);
                            makeThumbnailImage($src, $dest, $thumbnailImgHeight, $imgFileType);

                            $data['p_simage'] = $thumbnailFileName; // psimage 값 설정

                        }
                    }
                }
            }


            $data['p_num'] = $getPnum;
            $data['p_category'] = isset($_REQUEST['p_category']) ? $_REQUEST['p_category'] : null;
            $data['p_code'] = $data['p_category'] . strval($getPnum);
            $data['p_name'] = isset($_REQUEST['p_name']) ? $_REQUEST['p_name'] : null;
            $data['p_stock'] = isset($_REQUEST['p_stock']) ? $_REQUEST['p_stock'] : null;
            $data['p_price'] = isset($_REQUEST['p_price']) ? $_REQUEST['p_price'] : null;
            $data['p_content'] = isset($_REQUEST['p_content']) ? $_REQUEST['p_content'] : null;

            $result = updateProductByNum($data);

            if (!$result) {
                exit(var_dump("상풍등록 실패"));
            } else {
                header("location:./MainCTL.php?action=930");
            }

            break;

        case 933:
            header("location:../view/MainView.php?action=$action");
            break;

        case 934://수정
            $pnum = $_REQUEST['num'];
            $product = selectProductbyNum($pnum);
            $product2 = selectimgbyNum($pnum, "product_img");
            if($product) {
                $_SESSION['productData'] = $product;
                $_SESSION['productData2'] = $product2;
                $action = 933;  //수정처리 뷰로 리다이렉트
            }
            header("location:../view/MainView.php?action=$action");
            break;

        case 936:
            $getPnum=$data['p_num'] = isset($_REQUEST['p_num']) ? $_REQUEST['p_num'] : null;
            $data['p_code'] = isset($_REQUEST['p_code'])?$_REQUEST['p_code']:null;

            $data['p_category'] = isset($_REQUEST['p_category']) ? $_REQUEST['p_category'] : null;
            $data['p_name'] = isset($_REQUEST['p_name']) ? $_REQUEST['p_name'] : null;
            $data['p_stock'] = isset($_REQUEST['p_stock']) ? $_REQUEST['p_stock'] : null;
            $data['p_price'] = isset($_REQUEST['p_price']) ? $_REQUEST['p_price'] : null;
            $data['p_content'] = isset($_REQUEST['p_content']) ? $_REQUEST['p_content'] : null;

            $current_image=isset($_REQUEST['p_files'])?$_REQUEST['p_files']:null;

            $Fileinfo=isset($_FILES['refiles'])?$_FILES['refiles']:null;

            $productImgSavePath = "../../img/product/";
            $table = "product_img";
            $fileMaxSize=200000;

            foreach($current_image as $current)
            {
                singleFileDelete($productImgSavePath,$current);
                deleteSelectImgboard($current,$table);
            }

            if ($Fileinfo) {
                $uploadPFimg = reArrayfiles($Fileinfo);

                $cnt = 1;
                if (isset($uploadPFimg[$cnt]['name'])) {
                    foreach ($uploadPFimg as $uploadFile) {

                        $imgFileType = pathinfo($uploadFile['name'], PATHINFO_EXTENSION); //이미지 파일 확장자 추출
                        $saveFile = $data['p_category'] . date("YmdHis") . strval($getPnum) . strval($cnt);
                        $saveFilename = strval($saveFile) . "." . strval($imgFileType);
                        $cnt++;

                        singleFileUpload($uploadFile, $productImgSavePath, $saveFilename, $fileMaxSize);

                        $data2['p_num'] = $getPnum;
                        $data2['savefile'] = $saveFilename;
                        $data2['filetype'] = $uploadFile['type'];

                        $result = insertMultiFiles($data2, $table);
                        if (!$result)
                            exit(var_dump("실패"));
                    }
                }
            }
            $result = updateProductByNum($data);

            $action = 930;
            $productPageInfo = $_SESSION['productPageInfo'];
            $ppageNum = $productPageInfo['currentPageNum'];
            header("location:./MainCTL.php?action=$action&ppageNum=$ppageNum");  //콘트롤러 재호출

            break;

        case 935:
            $savePath = "../../img/product/";
            $savePath2 = "../../img/product_s/";
            $table = "product_img";
            $pnum = $_REQUEST['num'];
            $p_simage = checkThumbnail($pnum);
            if ($p_simage != null) {
                singleFileDelete($savePath2, $p_simage);
            }
            if (getFileCountwithPnum($pnum, $table) > 0) {
                $fileList = selectimgbyNum($pnum, $table);
                foreach ($fileList as $file) {
                    singleFileDelete($savePath, $file['savefile']);
                }
                $result = deleteImgboard($pnum, $table);
            }
            $result = deleteProduct($pnum);

            header("location:./MainCTL.php?action=930");  //콘트롤러 재호출
            break;


        case 940:
            header("location:../view/MainView.php?action=$action");
            break;

        case 950:
            header("location:../view/MainView.php?action=$action");
            break;

        case 960:
            header("location:../view/MainView.php?action=$action");
            break;

        default:
            header("location:../view/MainView.php?action=$action");
            break;

    }
}