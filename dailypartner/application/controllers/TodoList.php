<?php

class TodoList extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

  
        /* start: database */
        $this->load->database();
        /* start: library */
        $this->load->library("error_reporter");
        $this->load->library("requestValue");
        $this->load->model('todo_model');
        /* end:   library */
    }

    public function index()
    {
        $alltodolist = $this->todo_model->alltodolist();
        $curdate = $this->todo_model->curdate();
        $dateintodo = $this->todo_model->dateintodo();
        $url = $_REQUEST['url'];
        $data['url'] = explode('/',$url);

        $data['curdate'] = $curdate;
        $data['alltodolist'] = $alltodolist;
        $data['dateintodo'] = $dateintodo;

        $this->load->view("todo/TodoList", $data);
    }

    public function NoteTodo()
    {
        $alltodolist = $this->todo_model->alltodolist();
        $curdate = $this->todo_model->curdate();
        $dateintodo = $this->todo_model->dateintodo();

        $data['curdate'] = $curdate;
        $data['alltodolist'] = $alltodolist;
        $data['dateintodo'] = $dateintodo;

        $this->load->view("todo/Note_todo", $data);
    }

    public function selecttodoindate()
    {

        if ($_POST) {

            $selectData = new requestValue();
            $selectData->insertProperty($_POST);
            $curdate = $this->todo_model->curdate();

            $data['curdate'] = $curdate;
            $data['todoIndate'] = $this->todo_model->selecttodoindate($selectData);
            $data['selectData'] = $selectData;

            $this->load->view("todo/Date_todo", $data);
        }
    }

    public function ListTodo()
    {

        $todolist = $this->todo_model->todolist();
        $curdate = $this->todo_model->curdate();

        $data['curdate'] = $curdate;
        $data['todolist'] = $todolist;

        $this->load->view("todo/List_todo", $data);

    }
    public function dashboardTodo()
    {
        if ($_POST) {

            $selectData = new requestValue();
            $selectData->insertProperty($_POST);

            $data['todoIndate'] = $this->todo_model->selecttodoindate($selectData);
            $data['selectData'] = $selectData;

            $this->load->view("todo/dashboard_todo", $data);
        }
    }

    public function deletetodo()
    {
        if ($_POST) {

            $deleteData = new requestValue();
            $deleteData->insertProperty($_POST);

            $this->todo_model->deletetodo($deleteData);
        }
    }

    public function inserttodo()
    {
        if ($_POST) {

            $insertData = new requestValue();
            $insertData->insertProperty($_POST);

            $worknum = $this->todo_model->inserttodo($insertData);
            $content['todoList'] = $this->todo_model->selecttodo($worknum);

            $this->load->view("todo/insert_todo", $content);
        }
    }

    public function modifytodo()
    {

        if ($_POST) {

            $modifyData = new requestValue();
            $modifyData->insertProperty($_POST);

            $this->todo_model->modifytodo($modifyData);

            $content = $this->todo_model->selecttodo($modifyData->work_num);

            echo json_encode($content);

        }
    }

    public function checktodo()
    {
        if ($_POST) {

            $checkData = new requestValue();
            $checkData->insertProperty($_POST);

            $check = $this->todo_model->selecttodo($checkData->work_num);

            if ($check->confirm_check == "0") {
                $this->todo_model->checktodo($checkData);
            } else {
                $this->todo_model->notchecktodo($checkData);
            }

            $result = $this->todo_model->selectResult($checkData->work_num);

            if($result->result==""){
                echo json_encode(0);
            }
            else{
                echo json_encode(1);
            }
        }
    }
    public function enrollResult()
    {
        if ($_POST) {

            $resultData = new requestValue();
            $resultData->insertProperty($_POST);

            $this->todo_model->enrollResult($resultData);

        }
    }
}