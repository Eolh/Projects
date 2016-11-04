<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
    Ajax Handler
    Ajax 요청에 대한 모든 처리를 담당
    MODEL의 경우는 모든 SQL문에 따라서 따른 MODEL 파일을 가져온다.
*/

class AjaxHandler extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        /* start of database */
        $this->load->database();
        $this->load->model('CalendarANDSchedule');
        $this->load->model('Group');
        $this->load->model('TagANDPersonal_tag');
        $this->load->model('FileANDUserFile');
        $this->load->model('Todo_model');
        /* end of database */

        ///////////////////////////////////////////////////////////////////

        /* start of library */
        $this->load->library("error_reporter");
        $this->load->library("presenter");
        $this->load->library("requestValue");
        /* end of library */

        /* start of helper */
        $this->load->helper('date');
        /* end of helper */
    }

    /***********************************************************************************************/
    /* START: INSERT                    */
    /***********************************************************************************************/

    function insertGroup()
    {
        /*$_POST['groupName'] = "2";*/

        if ($_POST) {
            $ajaxData = new requestValue();
            $ajaxData->insertProperty($_POST);
            $ajaxData->UID = $_SESSION['login']['UID'];
            $ajaxData->date = unix_to_human(time(), true, 'asia');

            $ajaxData = $this->Group->insertGroup($ajaxData);

            if ($ajaxData->check) {
                $result = $this->CalendarANDSchedule->insertUserCalendar($ajaxData);

                echo json_encode($result);
            }
        }
    }

    function insertCalendarSchedule()
    {
        if ($_POST) {
            /* start: modelHandler */
            $ajaxData = new requestValue();
            $ajaxData->insertProperty($_POST);
            $ajaxData->UID = $_SESSION['login']['UID'];
            $ajaxData->ID = $_SESSION['login']['ID'];
            /* end:   modelHandler */

            $last_ID = $this->CalendarANDSchedule->insertCalendarANDSchedule($ajaxData);
            /* 일정 등록 */
            $this->CalendarANDSchedule->insertSchedulePlace($ajaxData, $last_ID);
            /* 지도 등록 */
            $ajaxData = $this->getTagANDInsertFromPOST($ajaxData);
            /* tag 등록 */

            if ($last_ID && !empty($_FILES['userFile']['name'][0])) /* 등록 및 파일이 있어야 하지! */ {
                /* start: fileUpload */
                $ajaxData->SdNum = $last_ID;
                $this->insertCalendarFile($ajaxData);
                /* end:   fileUpload */
            }
            echo json_encode($last_ID);
        }
    }

    function getTagANDInsertFromPOST($ajaxData)
    {
        $arr = explode(" ", $ajaxData->tag);

        if (!empty($arr)) /* 값이 존재하면 실행. */ {
            $arr = array_unique($arr); /* 중복 값을 제거 */

            $hashTag = "";

            foreach ($arr as $temp) {
                if ($temp != "") {
                    $last_id = $this->TagANDPersonal_tag->insertTag($temp);
                    $ajaxData->tagnum = $last_id;
                    $this->TagANDPersonal_tag->insertUserTag($ajaxData);
                    $hashTag .= $temp . ";"; /* 굳이 필요한가 싶긴하다... */
                }
            }
            $ajaxData->hashTag = $hashTag;
        }

        return $ajaxData;
    }

    function insertCalendarFile($ajaxData)
    {
        /* start: modelHandler */
        $ajaxData->insertProperty($_FILES);
        $ajaxData->filePath = $ajaxData->filePath . $_SESSION['login']['ID'] . "/";
        /* end:   modelHandler */ /* "./public/file/" */

        for ($x = 0; $x < count($ajaxData->userFile['name']); $x++) {
            /* start: get FNum */ /* 파일 관리할때 필요한 번호. */
            $ajaxData->lastFNum = $this->FileANDUserFile->getFNum();
            /* end:   get FNum */

            /* start: fileUpload */
            $ajaxData->number = $x;
            $presenter = new presenter();
            $ajaxData = $presenter->fileUpload($ajaxData); /* return bool */
            /* end:   fileUpload */

            /* file Model */
            if ($ajaxData->check) {
                /* start: fileModelRegister */
                $last_id = $this->FileANDUserFile->insertFile($ajaxData);
                $ajaxData->FNum = $last_id;
                $ajaxData->date = unix_to_human(time(), true, 'asia');
                $this->FileANDUserFile->insertUserFile($ajaxData);
                /* end:   fileModelRegister */
            }
        }
    }

    /***********************************************************************************************/
    /* END:   INSERT                    */
    /***********************************************************************************************/


    /***********************************************************************************************/
    /* START: UPDATE                    */
    /***********************************************************************************************/


    function updateCalendarScheduleToResize()
    {
        if ($_POST) {
            $ajaxData = new requestValue();
            $ajaxData->insertProperty($_POST);
            $this->CalendarANDSchedule->updateCalendarANDSchedule($ajaxData);
        }
    }


    /***********************************************************************************************/
    /* END:   UPDATE                    */
    /***********************************************************************************************/

    function getDetailSchedule()
    {
        if ($_POST) {
            $ajaxdata = new requestValue();
            $ajaxdata->insertProperty($_POST);
            $ajaxdata->UID = $_SESSION['login']['UID'];

            $value = $this->CalendarANDSchedule->getContent($ajaxdata->SdNum);

            echo json_encode($value);
        }
    }

    function getscheduleFile()
    {
        if ($_POST) {
            $ajaxdata = new requestValue();
            $ajaxdata->insertProperty($_POST);

            $value = $this->FileANDUserFile->getScheduleFile($ajaxdata->SdNum);
            echo json_encode($value);
        }
    }

    function getgroupFile()
    {
        if ($_POST) {
            $ajaxdata = new requestValue();
            $ajaxdata->insertProperty($_POST);

            $value = $this->FileANDUserFile->getGroupFile($ajaxdata->SdNum);
            echo json_encode($value);
        }
    }

    function getscheduleWithDate()
    {
        if ($_POST) {
            $ajaxdata = new requestValue();
            $ajaxdata->insertProperty($_POST);

            $value = $this->CalendarANDSchedule->getScheduleWithDate($ajaxdata);
            echo json_encode($value);
        }
    }

    function getUserFriend()
    {
        if ($_POST) {
            $ajaxdata = new requestValue();
            $ajaxdata->insertProperty($_POST);

            $value = $this->Group->getUserFriend($ajaxdata);

            echo json_encode($value);
        }
    }
}