<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('asia/seoul');
class Main extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        /* start of database */
        $this->load->database();
        $this->load->model('CalendarANDSchedule');
        $this->load->model('Group');
        $this->load->model('GroupFile');
        $this->load->model('FileANDUserFile');
        $this->load->model('Maps_m');
        $this->load->model('Todo_model');
        $this->load->model('GroupCalendar');
        $this->load->model('Board_m');
        /* end of database */

        ///////////////////////////////////////////////////////////////////

        /* start of library */
        $this->load->library("error_reporter");
        $this->load->library("presenter");
        $this->load->library("requestValue");
        $this->load->library("HashTag");
        /* end of library */

        /* start of helper */
        $this->load->helper('date');
        /* end of helper */
    }

    public function index()
    {
        /* start: modelHandler */
        $dashData = new requestValue();
        $dashData->UID   = $_SESSION['login']['UID'];
        $dashData->UCNum = 1; /* default Calendar */
        /* end:   modelHandler */
        $url = $_REQUEST['url'];
        $data['url'] = explode('/',$url);
        $data['groupInfo'] = $this->Group->getGroupList($dashData);

        $this->load->view("/main/dashboard", $data);
    }

    public function getFileListBySdNum()
    {
        if($_POST)
        {
            /* start: modelHandler */
            $dashData = new requestValue();
            $dashData->SdNum = $_POST['SdNum'];
            $dashData->UID   = $_SESSION[ 'login' ][ 'UID' ];
            /* end:   modelHandler */

            $data['tagConverter'] = new HashTag();
            $data['fileList'] = $this->FileANDUserFile->getFileListBySdNum($dashData);

            $this->load->view("/main/common/fileList", $data);
        }
    }

    public function getFileListByDate()
    {
        /* start: modelHandler */
        $dashData = new requestValue();
        $dashData->UID   = $_SESSION['login']['UID'];
        $dashData->UCNum = 1; /* default Calendar */
        $dashData->date  = date("Y-m-d");

        if(isset($_POST['date']))
            $dashData->date = $_POST['date'];

        /* end:   modelHandler */

        $data['tagConverter'] = new HashTag();
        $data['fileList'] = $this->FileANDUserFile->getFileListByDate($dashData);

        //getArgDumpDied($data['fileList']);

        //        if($data['fileList'])
        $this->load->view("/main/common/fileList", $data);
    }

    public function getCalendarData()
    {
        /* start: modelHandler */
        $dashData = new requestValue();
        $dashData->UID   = $_SESSION['login']['UID'];
        $dashData->UCNum = 1; /* default Calendar */
        /* end:   modelHandler */

        $data['groupInfo'] = $this->Group->getGroupList($dashData);

        $dashData->calNum = $data['groupInfo'][0]->calNum;
        $data['calendarList'] = $this->CalendarANDSchedule->getCalendarANDScheduleToMonth($dashData);
        if($data['calendarList'] == null)
        {
            $data['calendarList'] = new stdClass();
            $data['calendarList']->title = "";
            $data['calendarList']->start = "0000-00-00";
            $data['calendarList']->end   = "";
            $data['calendarList']->UCNum = $data['groupInfo'][0]->UCNum;
        }

        echo json_encode($data['calendarList']);
    }

    public function getExistFile()
    {
        /* start: modelHandler */
        $dashData = new requestValue();
        $dashData->UID   = $_SESSION['login']['UID'];
        $dashData->UCNum = 1;
        /* end:   modelHandler */

        $data['fileList'] = $this->FileANDUserFile->getFileList($dashData);

        echo json_encode($data['fileList']);
    }

    public function getExistDate()
    {
        /* start: modelHandler */
        $dashData = new requestValue();
        $dashData->UID   = $_SESSION['login']['UID'];
        $dashData->UCNum = 1; /* default Calendar */
        /* end:   modelHandler */

        $data = $this->CalendarANDSchedule->getDateList($dashData);

        echo json_encode($data);
    }

    public function loadplaces(){

        $ajaxData = new requestValue();
        $ajaxData->UID = $_SESSION['login']['UID'];
        $ajaxData->insertProperty($_POST);

        $value = $this->Maps_m->load_places($ajaxData);

        echo json_encode($value);
    }

    public function getFileList()
    {
        /* start: modelHandler */
        $dashData = new requestValue();
        $dashData->UID   = $_SESSION['login']['UID'];
        $dashData->UCNum = 1; /* default Calendar */
        $dashData->date  = date("Y-m-d");

        if(isset($_POST['date']))
            $dashData->date = $_POST['date'];

        /* end:   modelHandler */

        $data['tagConverter'] = new HashTag();
        $data['fileList'] = $this->FileANDUserFile->getFileListByDate($dashData);

        if($data['fileList'])
            $this->load->view("/main/common/fileList", $data);
    }

    public function getBriefing(){
        $ajaxData = new requestValue();
        $ajaxData->UID = $_SESSION['login']['UID'];

        $value['count'] = $this->CalendarANDSchedule->getBriefingCount($ajaxData);
        $value['schedule'] = $this->CalendarANDSchedule->getBriefing($ajaxData);
        $value['todo'] = $this->Todo_model->getTodoCount($ajaxData);

        echo json_encode($value);
    }

    /* 2016-07-26 groupTab + groupDashboard */

    public function getGroupTabList()
    {
        $ajaxData = new requestValue();
        $ajaxData->UID = $_SESSION['login']['UID'];

        $data['groupList'] = $this->GroupCalendar->getGroupTabList($ajaxData);

        $this->load->view("/main/common/tabList", $data);
    }

    public function getGroupDashBoard()
    {
        $ajaxData = new requestValue();
        $ajaxData->UID = $_SESSION['login']['UID'];

        $data['dashboardList'] = $this->GroupCalendar->getGroupTabList($ajaxData);

        for($x=0 ; $x<count($data) ; $x++)
            $data['dashboardList'][$x]->memberList = $this->GroupCalendar->getGroupAndMemberList($data['dashboardList'][$x], false);

        $this->load->view("/main/common/tabDashboardList", $data);
    }

    /* 2016-07-27 fullcalendar group events data */

    public function getGroupFullEvents($gnum)
    {
        $ajaxData = new requestValue();
        $ajaxData->UID  = $_SESSION['login']['UID'];
        $ajaxData->gnum = $gnum;

        $checkGroupMember = $this->GroupCalendar->getGroupCreate_by($ajaxData);

        if($checkGroupMember) {
            $data['fullEvents'] = $this->GroupCalendar->getGroupFullEventsByMaster($ajaxData);
        } else {
            $data['fullEvents'] = $this->GroupCalendar->getGroupFullEventsByMember($ajaxData);
        }

        echo json_encode($data['fullEvents']);
    }

    /* 2016-08-01 fullCalendar member events data */

    public function getMasterFullEvents($gnum) {
        $ajaxData = new requestValue();
        $ajaxData->gnum = $gnum;

        $data['fullEvents'] = $this->GroupCalendar->getGroupFullEventsByMaster($ajaxData);

        echo json_encode($data['fullEvents']);
    }

    public function getMemberFullEvents($gnum, $uid) {

        /* url getMemberFullEvents/1/1 mean gnum is 1 and uid is 1 */

        $ajaxData = new requestValue();
        $ajaxData->UID = $_SESSION['login']['UID'];
        $ajaxData->gnum = $gnum;
        $ajaxData->gm_id = $uid;

        $data['fullEvents'] = $this->GroupCalendar->getMemberFullEvents($ajaxData);

        echo json_encode($data['fullEvents']);
    }

    /* 2016-09-21 group fileList */

    public function getGroupFileList($gnum)
    {
        $ajaxData = new requestValue();
        $ajaxData->gnum = $gnum;

        if(isset($_POST['gm_id'])) {
            $ajaxData->gm_id = $_POST['gm_id'];
            $data['fileList'] = $this->GroupFile->getGroupFileListByMember($ajaxData);
        } else {
            $data['fileList'] = $this->GroupFile->getGroupFileList($ajaxData);
        }

        $this->load->view("/main/common/groupFileList", $data);
    }

    public function getGroupBaordList($gnum)
    {
        $ajaxData = new requestValue();
        $ajaxData->gnum = $gnum;

        $data['boardList'] = $this->Board_m->groupBoardAllCheck($ajaxData);

        $this->load->view("/main/common/boardListAllCheck", $data);

    }
}