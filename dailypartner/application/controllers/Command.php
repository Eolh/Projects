<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 로그인이 완료되면 대시보드로 이동한다.
 * 대시보드에 관한 CTL
 */
class Command extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        /* start of database */
        $this->load->database();

        /* start of library */
        $this->load->library("error_reporter");
        $this->load->library("requestValue");
        $this->load->model('UserModel');
        $this->load->model('CalendarANDSchedule');
        $this->load->model('group');

        /* end of library */
    }

    public function index()
    {
        /* start: modelHandler */
        $calendarData = new requestValue();
        $calendarData->UID = $_SESSION['login']['UID'];
        $calendarData->UCNum = 1;
        /* end:   modelHandler */

        /* send: data */
        $data['groupInfo'] = $this->group->getGroupList($calendarData);
        $temper = [];
        for ($x = 0; $x < count($data['groupInfo']); $x++) {
            $calendarData->calNum = $data['groupInfo'][$x]->calNum;
            $temper[$x] = $this->CalendarANDSchedule->getCalendarANDScheduleToMonth($calendarData);
        }
        $data['calendarList'] = $temper;
        /* end:  data */

        /* start: view */
        $this->load->view("/common/header_main", $data);
        /* end:   view */
    }

    public function searchUser()
    {
        if ($_POST) {
            $searchFriend = new requestValue();
            $searchFriend->insertProperty($_POST);

            $searchData = $this->UserModel->userSearchByName($searchFriend);

            echo json_encode($searchData);
        }
    }

    public function friendadd()
    {
        if ($_POST) {
            $friendAdd = new requestValue();
            $friendAdd->insertProperty($_POST);

            $friendnum = $this->UserModel->friendadd($friendAdd);

            echo json_encode($friendnum);
        }
    }

    public function frienddelete()
    {
        if ($_POST) {
            $friendDelete = new requestValue();
            $friendDelete->insertProperty($_POST);

            $friendID = $this->UserModel->frienddelete($friendDelete);

            echo json_encode($friendID);

        }
    }

    public function friendaddlistAdd()
    {
        if ($_POST) {
            $friendaddlistAdd = new requestValue();
            $friendaddlistAdd->insertProperty($_POST);

            $friendInfo = $this->UserModel->friendaddlistAdd($friendaddlistAdd);

            $data['friendInfo'] = $friendInfo;

            $this->load->view("/friendlistAdd", $data);

        }
    }

    public function friendaddlistDelete()
    {
        if ($_POST) {
            $friendaddlistDelete = new requestValue();
            $friendaddlistDelete->insertProperty($_POST);

            $this->UserModel->friendaddlistDelete($friendaddlistDelete);

        }
    }

    public function addgroup()
    {
        if ($_POST) {
            $groupName = new requestValue();
            $groupName->insertProperty($_POST);

            $curdate = $this->UserModel->curdate();
            $teamName = $this->UserModel->groupadd($groupName, $curdate);

            $data['teamName'] = $teamName;

            $this->load->view("/grouplistAdd", $data);
        }
    }

    public function grouplistView()
    {
        if ($_POST) {
            $groupname = new requestValue();
            $groupname->insertProperty($_POST);

            $gnum = $this->UserModel->grouplistNum($groupname);
            $groupMemberUID = $this->UserModel->SearchMember($gnum);

            for ($i = 0; $i < count($groupMemberUID); $i++) {
                $data['groupMemberList'][] = $this->UserModel->groupMemberpList($groupMemberUID[$i]);
            }

            $this->load->view("/groupmemberList", $data);
        }
    }
}