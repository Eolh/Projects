<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        /* start: database */
        $this->load->database();
        $this->load->model('CalendarANDSchedule');
        $this->load->model('group');
        /* end:   database */

        /* start: library */
        $this->load->library("error_reporter");
        $this->load->library("requestValue");
        $this->load->model('todo_model');
        /* end:   library */
    }

    public function index()
    {
        /* start: modelHandler */
        $calendarData = new requestValue();
        $calendarData->UID   = $_SESSION['login']['UID'];
        $url = $_REQUEST['url'];
        $data['url'] = explode('/',$url);
        /* end:   modelHandler */

        /* send: data */
        $data['groupInfo'] = $this->group->getGroupList($calendarData);

        $todolist = $this->todo_model->todolist();
        $curdate = $this->todo_model->curdate();

        $data['curdate'] = $curdate;
        $data['todolist'] = $todolist;
        /* end:  data */

        /* start: view */
        $this->load->view("/calendar/fullcalendar", $data);
        /* end:   view */
    }

    public function getCalendarList($UCNum = 1)
    {
        /* start: modelHandler */
        $calendarData = new requestValue();
        $calendarData->UID   = $_SESSION['login']['UID'];
        $calendarData->UCNum = $UCNum;
        /* end:   modelHandler */

        $calendarData->calNum = $this->group->getCalNumByUCNum($calendarData);

        $temper = $this->CalendarANDSchedule->getCalendarANDScheduleToMonth($calendarData);
        if($temper == null)
        {
            $temper = new stdClass();
            $temper->title = "";
            $temper->start = "0000-00-00";
            $temper->end   = "";
            $temper->UCNum = $calendarData->UCNum;
        }

        $data['calendarList'] = $temper;

        echo json_encode($data['calendarList']);
    }
}