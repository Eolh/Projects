<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
    Android Handler
    Android에서 필요한 모든 JSON DATA에 대한 HANDLER CTL
    MODEL의 경우는 모든 SQL문에 따라서 따른 MODEL 파일을 가져온다.
 */
date_default_timezone_set('asia/seoul');
class AndroidHandler extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /* start of database */
        $this->load->database();
        $this->load->model('AndroidParsing');
        $this->load->model('group');
        /* end of database */

        /* start of library */
        $this->load->library("error_reporter");
        $this->load->library("requestValue");
        /* end of library */
    }

    public function test()
    {
        $result = $this->AndroidParsing->getAll();

        echo json_encode($result);
    }

    public function insertSchedule()
    {
        if($_POST) {
            $_POST['endTime'] = date("Y-m-d H:i", strtotime($_POST['endTime']." +30 minute"));

            $androidData = new requestValue();
            $androidData->insertProperty($_POST);

            $this->AndroidParsing->insertCalendarANDSchedule($androidData);
        }
    }

    public function getAutoList()
    {
        $androidData = new requestValue();
        $androidData->UID = $_SESSION['login']['UID'];

        $data['autoList'] = $this->AndroidParsing->getAutoList($androidData);

        for($x=0 ; $x<count($data['autoList']) ; $x++)
        {
            $androidData->UCNum = $data['autoList'][$x]->UCNum;
            $data['autoList'][$x]->calName = $this->AndroidParsing->getCalendarName($androidData);
        }

        $this->load->view("/common/autoRegisterTable", $data);
    }

    public function getMultiCalendar()
    {
        $androidData = new requestValue();
        $androidData->UID = $_SESSION['login']['UID'];

        $data['multiCalendar'] = $this->group->getGroupList($androidData);

        echo json_encode($data['multiCalendar']);
    }

    public function insertParsing()
    {
        if($_POST) {
            $androidData = new requestValue();
            $androidData->UID = $_SESSION['login']['UID'];
            $androidData->insertProperty($_POST);

            $last_id = $this->AndroidParsing->insertParsing($androidData);
            $data['autoLister'] = $this->AndroidParsing->getParsing($last_id);

            $androidData->UCNum = $data['autoLister']->UCNum;
            $data['autoLister']->calName = $this->AndroidParsing->getCalendarName($androidData);

            $this->load->view("/common/autoTableTbody", $data);
        }
    }
}
