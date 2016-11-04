<?php

/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2016-05-11
 * Time: 오전 1:15
 */
class Maps extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Maps_m');
        $this->load->model('group');

        $this->load->helper(array('url', 'date'));

        /* start of library */
        $this->load->library("error_reporter");
        $this->load->library("requestValue");
        $this->load->model('todo_model');
        /* end of library */
    }

    function index()
    {
        $this->write();
    }

    function write()
    {
        if ($_POST) {
            for ($i = 0; $i < count($_POST['value']); $i = $i + 3) {

                $data['lat'] = $this->input->post('[value][' . $i . ']', TRUE);
                $data['lng'] = $this->input->post('[value][' . ($i + 1) . ']', TRUE);
                $data['address'] = $this->input->post('[value][' . ($i + 2) . ']', TRUE);
                $this->Maps_m->insert_maps($data);
            }

            redirect('/Maps');

            exit;
        } else {

            $todolist = $this->todo_model->todolist();
            $curdate = $this->todo_model->curdate();
            $url = $_REQUEST['url'];
            $data['url'] = explode('/',$url);

            $data['curdate'] = $curdate;
            $data['todolist'] = $todolist;

            $this->load->view('map/maps', $data);
        }
    }


    function loadschedule()
    {
        if ($_POST) {
            $ajaxData = new requestValue();
            $ajaxData->insertProperty($_POST);
            $ajaxData->UID = $_SESSION['login']['UID'];
            $loadData = $this->Maps_m->load_schedule($ajaxData);

            echo json_encode($loadData);
        }
    }


}

?>