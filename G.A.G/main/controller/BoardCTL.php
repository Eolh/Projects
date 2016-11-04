<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-12-13
 * Time: 오후 6:31
 */
include_once "../model/BoardDAO.php";
include_once "../model/memberDAO.php";
include_once "../model/replyDAO.php";
function BoardController($action)
{
    $category = isset($_REQUEST['category']) ? $_REQUEST['category'] : null;

    switch ($action) {

        case 800: // 게시글 리스트
            $ClppandCppb = array(10, 10);
            $tableName = $category . "board";
            $pageParaName = "bpageNum" . strval($category);
            $pageInfoName = "bpageInfo" . strval($category);
            $pageInfo = isset($_SESSION[$pageInfoName]) ? $_SESSION[$pageInfoName] : null;

            if (!$pageInfo) // 기존 페이지정보 객체가 없다면
                $pageNum = isset($_REQUEST[$pageParaName]) ? $_REQUEST[$pageParaName] : 1;
            else
                $pageNum = isset($_REQUEST[$pageParaName]) ? $_REQUEST[$pageParaName] : 1;

            $cnt = getBoardCountWithcategory($tableName, $_SESSION['search']); // product 테이블의 카테고리 레코드 갯수 조회
            $boardPageInfo = getPageInfo($pageNum, $cnt, $ClppandCppb[0], $ClppandCppb[1]);
            $boardList = selectBoardListWithPageInfoAndCategory($boardPageInfo, $tableName, $_SESSION['search']);
            $_SESSION[$pageInfoName] = $boardPageInfo;
            $_SESSION['boardList'] = $boardList;
            header("location:../view/MainView.php?action=$action&category=$category");  //콘트롤러 재호출
            break;

        case 810: // 글쓰기
            header("location:../view/MainView.php?action=$action&category=$category&point={$_REQUEST['point']}");
            break;

        case 811: // 글쓰기 처리 액션

            $productImgSavePath = "../../img/board/";
            $fileMaxSize = 20000000; // 파일 최대 크기 2Mbyte 설정
            $table_name = $category . "_img";
            $m_id = $_REQUEST['m_id'];

            $m_num = selectMnumByID($m_id);

            $data['category'] = $category;
            $data['m_num'] = $m_num;
            $data['b_table'] = $category . strval("board");
            $data['b_subject'] = isset($_REQUEST['b_subject']) ? $_REQUEST['b_subject'] : null;
            $data['b_content'] = isset($_REQUEST['b_content']) ? $_REQUEST['b_content'] : null;

            date_default_timezone_set('Asia/Seoul');
            $registdate = date("Y-m-d/H:i");
            $data['b_date'] = $registdate;

            $getBnum = insertBoard($data);


            if ($_FILES['b_files']) {       //이미지 등록
                $uploadFimg = reArrayfiles($_FILES['b_files']);

                $cnt = 0;
                if (isset($uploadFimg[$cnt]['tmp_name'])) {
                    foreach ($uploadFimg as $uploadFile) {

                        $imgFileType = pathinfo($uploadFile['name'], PATHINFO_EXTENSION); //이미지 파일 확장자 추출
                        $saveFile = $data['category'] . date("YmdHis") . strval($getBnum) . strval($cnt + 1);
                        $saveFilename = strval($saveFile) . "." . strval($imgFileType);
                        $cnt++;

                        singleFileUpload($uploadFile, $productImgSavePath, $saveFilename, $fileMaxSize);

                        $data2['p_num'] = $getBnum;
                        $data2['savefile'] = $saveFilename;
                        $data2['filetype'] = $uploadFile['type'];

                        $result = insertMultiFiles($data2, $table_name);
                        if (!$result)
                            exit(var_dump("실패"));
                    }
                }
            }

            header("location:./MainCTL.php?action=800&category=$category");

            break;

        case 812:
            $savePath = "../../img/board/";
            $table = $category . strval("_img");
            $table2 = $category . strval("board");
            $bnum = $_REQUEST['bnum'];
            if (getFileCountwithPnum($bnum, $table) > 0) {
                $fileList = selectimgbyNum($bnum, $table);
                foreach ($fileList as $file) {
                    singleFileDelete($savePath, $file['savefile']);
                }
                $result = deleteImgboard($bnum, $table);
            }
            $result = deleteBoard($bnum, $table2);
            header("location:./MainCTL.php?action=800&category=$category");  //콘트롤러 재호출

            break;

        case 815://댓글 입력
            $tableName = strval($category) . "_reply";


            $data['p_num'] = isset($_REQUEST['bnum']) ? $_REQUEST['bnum'] : null;
            $data['m_id'] = isset($_REQUEST['m_id']) ? $_REQUEST['m_id'] : null;
            $data['r_content'] = isset($_REQUEST['content']) ? $_REQUEST['content'] : null;

            $data['m_num'] = selectMnumByID($data['m_id']);

            date_default_timezone_set('Asia/Seoul');
            $registdate = date("Y-m-d/H:i");
            $data['r_date'] = $registdate;

            $result = insertreplyComment($data, $tableName);
            $action = 820; // 상세페이지 보기 뷰로 리다이렉트
            header("location:../controller/MainCTL.php?action=$action&category=$category&bnum={$data['p_num']}");


            break;
        case 816://댓글 삭제
            $tableName = strval($category) . "_reply";

            $bnum = $_REQUEST['bnum'];
            $r_num = $_REQUEST['r_num'];
            deleteReplyByNum($r_num, $tableName);
            header("location:../controller/MainCTL.php?action=820&category=$category&bnum=$bnum");
            break;

        case 818: // reply 데이터 수정 처리

            $tableName = strval($category) . "_reply";


            $rnum = $_REQUEST['r_num'];
            $bnum = $_REQUEST['bnum'];

            $data['rnum'] = isset($_REQUEST['r_num']) ? $_REQUEST['r_num'] : null;
            $data['content'] = isset($_REQUEST['content']) ? $_REQUEST['content'] : null;

            updateReplyByNum($data, $tableName);
            $action = 820;

            header("location:../controller/MainCTL.php?action=$action&category=$category&bnum=$bnum");

            break;

        case 819:
            $productImgSavePath = "../../img/board/";
            $fileMaxSize = 20000000; // 파일 최대 크기 2Mbyte 설정
            $table_name = $category . "_img";
            $table = $category."board";
            $m_id = $_REQUEST['m_id'];
            $point = $_REQUEST['point'];
            var_dump($point);
            $parentdata = getparentdata($point,$table);

            $m_num = selectMnumByID($m_id);

            $data['category'] = $category;
            $data['m_num'] = $m_num;
            $data['b_table'] = $table;
            $data['b_subject'] = isset($_REQUEST['b_subject']) ? $_REQUEST['b_subject'] : null;
            $data['b_content'] = isset($_REQUEST['b_content']) ? $_REQUEST['b_content'] : null;

            date_default_timezone_set('Asia/Seoul');
            $registdate = date("Y-m-d/H:i");
            $data['b_date'] = $registdate;

            if ($data['b_subject'] && intval($parentdata['b_depth']) + 1 <= 5)    // 제목이 있어야지 입력가능!
            {
                $getBnum = insertreBoard($data,$parentdata);
            }

            if ($_FILES['b_files']) {       //이미지 등록
                $uploadFimg = reArrayfiles($_FILES['b_files']);

                $cnt = 0;
                if (isset($uploadFimg[$cnt]['name'])) {
                    foreach ($uploadFimg as $uploadFile) {

                        $imgFileType = pathinfo($uploadFile['name'], PATHINFO_EXTENSION); //이미지 파일 확장자 추출
                        $saveFile = $data['category'] . date("YmdHis") . strval($getBnum) . strval($cnt + 1);
                        $saveFilename = strval($saveFile) . "." . strval($imgFileType);
                        $cnt++;

                        singleFileUpload($uploadFile, $productImgSavePath, $saveFilename, $fileMaxSize);

                        $data2['p_num'] = $getBnum;
                        $data2['savefile'] = $saveFilename;
                        $data2['filetype'] = $uploadFile['type'];

                        $result = insertMultiFiles($data2, $table_name);
                        if (!$result)
                            exit(var_dump("실패"));
                    }
                }
            }

            header("location:./MainCTL.php?action=800&category=$category");

            break;


        case 820: // 글보기 뷰로 디다이렉트


            $bnum = $_REQUEST['bnum'];
            $imgTable = $category . "_img";
            $b_table = $category . strval("board");
            $re_table = $category . "_reply";
            $boardView = $category . "board";
            $boardView2 = $boardView . "_img";

            increaseHit($bnum, $b_table); // 글 조회수 증가

            $board = selectBoardbyNum($bnum, $b_table);
            $board['m_id'] = selectIDByNum($board['m_num']);
            $board2 = selectimgbyNum($bnum, $imgTable);
            $replylist = selectReplyByNum($bnum, $re_table);


            for ($i = 0; $i < count($replylist); $i++) {
                $replylist[$i]['m_id'] = selectIDByNum($replylist[$i]['m_num']);
            }

            $_SESSION[$boardView] = $board;
            $_SESSION[$boardView2] = $board2;
            $_SESSION["replyView"] = $replylist;
            $updateRnum = isset($_REQUEST['updateRnum']) ? $_REQUEST['updateRnum'] : null;
            header("location:../view/MainView.php?action=$action&category=$category&bnum=$bnum&updateRnum=$updateRnum");
            break;

        default:
            header("location:../view/MainView.php?action=$action");
            break;
    }
}

?>