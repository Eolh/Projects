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
        $this->load->model('Group');
        $this->load->model('FriendANDGroup');
        $this->load->model('Board_m');

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
}