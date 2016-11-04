<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-11-06
 * Time: 오후 3:26
 */
include_once "../model/memberDAO.php";
function loginController($action)
{

    switch ($action) {

        case 10: // 로그인 관련 처리 액션
            header("location:../view/MainView.php?action=$action");  //콘트롤러 재호출
            break;

        case 11: // 로그인 처리 액션
            $userID = $_REQUEST['user_id'];
            $userPW = $_REQUEST['user_pwd'];

            $resultArr = loginCheck($userID, $userPW);

            if ($resultArr['code'] == 1) {
                $_SESSION['loginID'] = $userID;
                $_SESSION['userLevel'] = $resultArr['level'];
                $action = 0;
            } else if ($resultArr['code'] == -1) {
                $action = 10;
            } else if ($resultArr['code'] == -2) {
                $action = 10;
            } else {
                $action = 10;
            }


            header("location:../view/MainView.php?action=$action");
            break;

        case 12: // 로그아웃 처리 액션
            unset($_SESSION['loginID']);
            unset($_SESSION['userLevel']);
            session_destroy();

            $msg = "로그 아웃 처리 되었습니다.";

            $_SESSION['msg'] = $msg;

            $action = 0;
            header("location:../view/MainView.php?action=$action");
            break;

        case 13: // 회원정보 입력 뷰로 디다이렉트
            header("location:../view/MainView.php?action=$action");
            break;

        case 14:  // 회원정보 입력 처리 액션
            $data['id'] = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : null;
            $data['password'] = isset($_REQUEST['user_pwd']) ? $_REQUEST['user_pwd'] : null;
            $data['name'] = isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : null;
            $data['tel'] = isset($_REQUEST['user_tel']) ? $_REQUEST['user_tel'] : null;

            $result = insertMember($data);

            $action = 0;
            header("location:../view/MainView.php?action=$action");
            break;

        default:
            header("location:../view/MainView.php?action=$action");
            break;
    }
}

?>