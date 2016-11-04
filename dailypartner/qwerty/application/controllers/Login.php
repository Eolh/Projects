<?php

class Login extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        /* start of database */
        $this->load->database();
        $this->load->model('Login_m');

        /* end of database */

        ///////////////////////////////////////////////////////////////////

        /* start of library */
        $this->load->library("error_reporter");
        $this->load->library("requestValue");
        /* end of library */
    }

    public function index()
    {
        $this->load->view("/login/title");
    }

    public function user_check()
    {
        if ($_POST) {
            $ajaxData = new requestValue();
            $ajaxData->insertProperty($_POST);
            $rec = $this->Login_m->checkAccount($ajaxData);

            if ($rec) {
                $_SESSION['login']['ID']  = $rec[0]->ID;
                $_SESSION['login']['UID'] = $rec[0]->UID;

                echo json_encode(true);
            } else {//x
                $modalName = '#login-join'; //값이 아예없을경우

                echo json_encode($modalName);
            }
        } else {//x
            $modalName = '#login-error';

            echo json_encode($modalName);
        }
    }

    function test($values)
    {
        echo "<pre>";
        var_dump($values);
        echo "</pre>";
    }
}