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
        /* end:   library */
    }

    function setMove()
    {
        echo "==>".var_dump(iconv_get_encoding("all"))."<br>";
    }

    public function index()
    {
        /* start: modelHandler */
        $calendarData = new requestValue();
        $calendarData->UID   = $_SESSION['login']['UID'];
        $calendarData->UCNum = 1;
        /* end:   modelHandler */

        /* send: data */
        $data['groupInfo'] = $this->group->getGroupList($calendarData);
        $temper = [];
        for($x=0 ; $x<count($data['groupInfo']) ; $x++)
        {
            $calendarData->calNum = $data['groupInfo'][$x]->calNum;
            $temper[$x] = $this->CalendarANDSchedule->getCalendarANDScheduleToMonth($calendarData);
            if($temper[$x] == null)
            {
                $temper[$x][0] = new stdClass();
                $temper[$x][0]->title = "";
                $temper[$x][0]->start = "0000-00-00";
                $temper[$x][0]->end   = "";
                $temper[$x][0]->UCNum = $data['groupInfo'][$x]->UCNum;
            }
        }
        $data['calendarList'] = $temper;
        /* end:  data */

        //getArgDumpDied($data['calendarList']);

        /* start: view */
        $this->load->view("/calendar/fullcalendar", $data);
        /* end:   view */
    }
}