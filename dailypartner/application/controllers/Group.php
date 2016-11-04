<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        /* start of database */
        $this->load->database();
        $this->load->model("TagANDPersonal_tag");
        $this->load->model("GroupCalendar");
        $this->load->model("GroupFile");
        $this->load->model("Maps_m");
        $this->load->model('Board_m');
        /* end of database */

        ///////////////////////////////////////////////////////////////////

        /* start of library */
        $this->load->library("error_reporter");
        $this->load->library("presenter");
        $this->load->library("requestValue");
        $this->load->library("HashTag");
        /* end of library */

        date_default_timezone_set('Asia/Seoul');
        /* start of helper */
        $this->load->helper('date');
        /* end of helper */
    }

    /* 2016-07-28 */

    public function calendar($gnum)
    {
        $calendarData = new requestValue();
        $calendarData->UID = $_SESSION['login']['UID'];
        $calendarData->gnum = $gnum;
        $url = $_REQUEST['url'];
        $data['url'] = explode('/', $url);

        $data['groupList'] = $this->GroupCalendar->getGroupAndMemberList($calendarData);
        $data['groupList']->memberList = $this->GroupCalendar->getGroupAndMemberList($data['groupList'], false);

        $this->load->view("/group/fullCalendarByGroup", $data);
    }

    public function getGroupFullEvents($gnum)
    {
        $ajaxData = new requestValue();
        $ajaxData->UID = $_SESSION['login']['UID'];
        $ajaxData->gnum = $gnum;

        $checkGroupMember = $this->GroupCalendar->getGroupCreate_by($ajaxData);

        if ($checkGroupMember) {
            $data['fullEvents'] = $this->GroupCalendar->getGroupFullEventsByMaster($ajaxData);
        } else {
            $data['fullEvents'] = $this->GroupCalendar->getGroupFullEventsByMember($ajaxData);
        }

        echo json_encode($data['fullEvents']);
    }

    /* 2016-08-04 */

    public function updateGroupCalendarSchedule() // update Resize && Drop
    {
        if ($_POST) {
            $ajaxData = new requestValue();
            $ajaxData->UID = $_SESSION['login']['UID'];
            $ajaxData->insertProperty($_POST);

            $this->GroupCalendar->updateGroupCalendarEvent($ajaxData);
        }
    }

    /* 2016-08-05 */

    public function insertGroupCalendarSchedule()
    {
        if ($_POST) {
            $ajaxData = new requestValue();
            $ajaxData->insertProperty($_POST);
            $ajaxData->UID = $_SESSION['login']['UID'];


            $last_id = $this->GroupCalendar->insertGroupCalendarSchedule($ajaxData);



            $ajaxData = $this->getTagANDInsertFromPOST($ajaxData);

                $ajaxData->SdNum = $last_id;
                $this->GroupCalendar->insertGroupSchedulePlace($ajaxData);
            /* check next time */
            if ($last_id && !empty($_FILES['userFile']['name'][0])) {
                $this->insertGroupCalendarFile($ajaxData);
            }

            echo json_encode($last_id);
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

    public function insertGroupCalendarFile($ajaxData)
    {
        $ajaxData->insertProperty($_FILES);
        $ajaxData->filePath = $ajaxData->filePath . "group_" . $ajaxData->gnum . "/";

        for ($x = 0; $x < count($ajaxData->userFile['name']); $x++) {

            $ajaxData->lastFNum = $this->GroupFile->getFnum();

            $ajaxData->number = $x;
            $presenter = new presenter();
            $ajaxData = $presenter->fileGroupUpload($ajaxData);

            if ($ajaxData->check) {

                $last_id = $this->GroupFile->insertFile($ajaxData);
                $ajaxData->FNum = $last_id;
                $ajaxData->date = unix_to_human(time(), true, 'asia');
                $this->GroupFile->insertGroupFile($ajaxData);
            }
        }
    }

    /* 2016-08-08 */

    public function file($gnum, $tag = null)
    {
        /* start: modelHandler */
        $fileData = new requestValue();
        $fileData->UID = $_SESSION['login']['UID'];
        $fileData->tag = $tag;
        $fileData->gnum = $gnum;
        /* end:   modelHandler */

        $data['tagConverter'] = new HashTag();
        $data['tagList'] = $this->TagANDPersonal_tag->getTagListByUID($fileData);
        $data['cntFiles'] = $this->TagANDPersonal_tag->getCountFiles($fileData);
        $data['selectedName'] = $tag;
        $data['gnum'] = $gnum;
        $url = $_REQUEST['url'];
        $data['url'] = explode('/', $url);

        /* 현재 페이지의 파일에 대한 리스트 */

        $data['groupList'] = $this->GroupCalendar->getGroupListByGnum($fileData);

        $data['groupCntFiles'] = 0;
        $data['groupList']->memberList = $this->GroupCalendar->getGroupAndMemberList($data['groupList'], false);
        for ($x = 0; $x < count($data['groupList']->memberList); $x++) {
            $data['groupList']->memberList[$x]->fileList = $this->GroupFile->getMemberFileList($data['groupList']->memberList[$x]);
            $data['groupCntFiles'] += $data['groupList']->memberList[$x]->fileList->cnt;
        }

        for ($x = 0; $x < count($data['tagList']); $x++) {
            $fileData->hashTag = $data['tagList'][$x]->tagName;
            $data['tagList'][$x]->fileList = $this->TagANDPersonal_tag->getFileListByTag($fileData);

        }   /* 태그 별로 수를 세기 위함... */

        $this->load->view('/group/file_list', $data);
    }

    /* 2016-08-09 */

    public function fileLister()
    {
        /* start: modelHandler */
        $fileData = new requestValue();
        $fileData->UID = $_SESSION['login']['UID'];
        $fileData->tag = isset($_POST['tag']) ? $_POST['tag'] : null;
        /* end:   modelHandler */

        $data['tagConverter'] = new HashTag();

        /* 유저가 가진 모든 회원 리스트 출력 */
        if ($fileData->tag != null)
            $data['fileList'] = $this->TagANDPersonal_tag->getFileListByUIDAndTag($fileData);
        else
            $data['fileList'] = $this->TagANDPersonal_tag->getFileListByUID($fileData);

        /* 현재 페이지의 파일에 대한 리스트 */

        $this->load->view('/file/fileLister', $data);
    }

    public function fileGroupList()
    {
        $fileData = new requestValue();
        $fileData->insertProperty($_POST);

        if ($fileData->gm_id != "")
            $data['fileGroupList'] = $this->GroupFile->getFileGroupList($fileData);
        else
            $data['fileGroupList'] = $this->GroupFile->getFileGroupListToGroup($fileData);

        $this->load->view('group/fileGroupLister', $data);
    }

    public function getMember()
    {
        $fileData = new requestValue();
        $fileData->insertProperty($_POST);

        $memberName = $this->GroupFile->getMemberName($fileData);

        echo $memberName;
    }

    public function fileListerGroupSearch()
    {
        $fileData = new requestValue();
        $fileData->insertProperty($_POST);

        if ($fileData->gm_id != "all")
            $data['fileGroupList'] = $this->GroupFile->getFileGroupListBySearch($fileData);
        else
            $data['fileGroupList'] = $this->GroupFile->getFileGroupListToGroupBySearch($fileData);

        //echo json_encode($_POST);
        $this->load->view('group/fileGroupLister', $data);
    }

    public function group($gnum)
    {
        $url = $_REQUEST['url'];
        $data['url'] = explode('/',$url);
        $ajaxData = new requestValue();
        $ajaxData->gnum = $gnum;

        $data['gname'] = $this->Maps_m->getGroupName($ajaxData->gnum);
        $data['grouplist'] = $this->Maps_m->group_members($ajaxData->gnum);
        $data['gnum'] = $ajaxData->gnum;
//        exit(var_dump($data));
        $this->load->view('group/group_maps', $data);

    }

    function getgroupFile()
    {
        if ($_POST) {
            $ajaxdata = new requestValue();
            $ajaxdata->insertProperty($_POST);

            $value = $this->GroupCalendar->getGroupFile($ajaxdata->SdNum);
            echo json_encode($value);
        }
    }

    public function getGroupSchedule()
    {

        if ($_POST) {
            $ajaxdata = new requestValue();
            $ajaxdata->insertProperty($_POST);

            $value = $this->GroupCalendar->getContent($ajaxdata->SdNum);

            echo json_encode($value);
        }
    }

    public function checked()
    {
        if ($_POST) {
            $ajaxdata = new requestValue();
            $ajaxdata->insertProperty($_POST);

            $this->groupClaendar->checked($ajaxdata->SdNum);
        }
    }

    public function loadschedule()
    {
        if ($_POST) {
            $ajaxData = new requestValue();
            $ajaxData->insertProperty($_POST);

            $loadData = $this->Maps_m->group_schedule($ajaxData);

            echo json_encode($loadData);
        }
    }

    public function loadplace()
    {
        if ($_POST) {
            $ajaxData = new requestValue();
            $ajaxData->insertProperty($_POST);
            $loadData = $this->Maps_m->group_place($ajaxData);

            echo json_encode($loadData);
        }
    }

    public function get_member_count()
    {

        if ($_POST) {
            $ajaxData = new requestValue();
            $ajaxData->insertProperty($_POST);
            $loadData = $this->Maps_m->get_member_info($ajaxData);

            echo json_encode($loadData);
        }
    }

    function boardList($data)
    {
        $groupNum = $data;

        $list['boardList'] = $this->Board_m->groupBoardList($groupNum);
        $url = $_REQUEST['url'];
        $list['url'] = explode('/', $url);

        $this->load->view("/group/boardList", $list);
    }

    function boardWriteView($data)
    {
        $userData = new requestValue();
        $userData->UID = $_SESSION['login']['UID'];
        $url = $_REQUEST['url'];
        $num['url'] = explode('/', $url);

        $num['groupNum'] = $data;
        $num['create_by'] = $this->Board_m->searchGroupCreate_by($num['groupNum']);
        $num['UID'] = $userData->UID;

        $this->load->view("/group/boardWriteView", $num);
    }

    function boardWriteSave($data)
    {
        if ($_POST) {

            $title = $_POST['title'];

            $content = $_POST['content'];

            $groupNum = $data;

            $createdate = $this->Board_m->now();

            $bnum = $this->Board_m->boardWriteSave($groupNum, $createdate, $title, $content);

            if ($_POST['allcheck']) {
                $this->Board_m->updateAllCheck($bnum);
            }

            $list['boardList'] = $this->Board_m->groupBoardList($groupNum);


            header("location:../boardList/$groupNum");
        }
    }

    function boardDetailView($data)
    {
        $userData = new requestValue();
        $userData->UID = $_SESSION['login']['UID'];

        $boardNum = $data;
        $url = $_REQUEST['url'];
        $list['url'] = explode('/', $url);
        $list['detailView'] = $this->Board_m->searchBaordDetail($boardNum);
        $list['groupName'] = $this->Board_m->searchGroupName($list['detailView'][0]->gnum);
        $list['commentView'] = $this->Board_m->searchComment($boardNum);
        $list['hitCount'] = $this->Board_m->updateHitCount($boardNum, $list['detailView'][0]->hit);
        $list['ID'] = $this->Board_m->SearchIDbyUID($userData->UID);

        $this->load->view("group/boardDetailView", $list);
    }

    function enrollComment()
    {
        if ($_POST) {
            $commentData = new requestValue();
            $commentData->insertProperty($_POST);

            $createdate = $this->Board_m->now();
            $enrollData = $this->Board_m->enrollComment($commentData, $createdate);

            echo json_encode($enrollData);
        }
    }

    function deleteComment()
    {
        if ($_POST) {
            $cnum = new requestValue();
            $cnum->insertProperty($_POST);

            $this->Board_m->deleteComment($cnum->cnum);

        }
    }

    function deleteBoard()
    {
        if ($_POST) {
            $boardData = new requestValue();
            $boardData->insertProperty($_POST);

            $this->Board_m->deleteBoard($boardData->bnum);

            echo json_encode($boardData->gnum);

        }
    }

    function scheduleMove()
    {
        if ($_POST) {
            $moveData = new requestValue();
            $moveData->insertProperty($_POST);

            $moveContent = $this->Board_m->moveContentBySdNum($moveData->SdNum); //이동시키기 전 데이터
            $UID = $this->Board_m->SearchUIDbygm_id($moveContent[0]->gm_id); // 사원 UID
            $ID = $this->Board_m->SearchIDbyUID($UID->UID);
            $createdate = $this->Board_m->Now();

            $this->Board_m->scheduleMoveEnroll($moveContent[0], $moveData, $createdate, $ID);

        }
    }

    public function importance_all_count(){
        $_POST['gnum']=32;
        if($_POST)
        {
            $ajaxData = new requestValue();
            $ajaxData->insertProperty($_POST);

            $loadData = $this->GroupCalendar->importance_all_count($ajaxData);

            echo json_encode($loadData);
        }
    }

    public function importance_count(){

        if($_POST){
            $ajaxData = new requestValue();
            $ajaxData->insertProperty($_POST);

            $loadData = $this->GroupCalendar->importance_count($ajaxData);

            echo json_encode($loadData);
        }
    }

    public function date_all_count(){
        if($_POST){
            $ajaxData = new requestValue();
            $ajaxData->insertProperty($_POST);

            $loadData = $this->GroupCalendar->date_all_count($ajaxData);

            echo json_encode($loadData);
        }
    }

    public function date_count(){
        if($_POST){
            $ajaxData = new requestValue();
            $ajaxData->insertProperty($_POST);

            $loadData = $this->GroupCalendar->date_count($ajaxData);

            echo json_encode($loadData);
        }
    }
    public function get_gm_id(){

        if($_POST){
            $ajaxData = new requestValue();
            $ajaxData->insertProperty($_POST);

            $loadData = $this->GroupCalendar->get_gm_id($ajaxData);

            echo json_encode($loadData);
        }
    }
}