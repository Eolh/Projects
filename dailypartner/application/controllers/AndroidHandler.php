<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
    Android Handler
    Android에서 필요한 모든 JSON DATA에 대한 HANDLER CTL
    MODEL의 경우는 모든 SQL문에 따라서 따른 MODEL 파일을 가져온다.
 */

class AndroidHandler extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        /* start of database */
        $this->load->database();
        $this->load->model('AndroidParsing');
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
}
