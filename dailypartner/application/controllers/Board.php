<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Board extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        /* start: database */
        $this->load->database();
        $this->load->model('Board_m');
        /* end:   database */

        /* start: library */
        $this->load->library("error_reporter");
        $this->load->library("requestValue");
        /* end:   library */
    }

    function index()
    {
        $userData = new requestValue();
        $userData->UID = $_SESSION['login']['UID'];

    }

    function boardList($data)
    {
        $groupNum = $data;

        $list['boardList'] = $this->Board_m->groupBoardList($groupNum);

        $this->load->view("/board/boardList", $list);
    }

    function boardWriteView($data)
    {
        $userData = new requestValue();
        $userData->UID = $_SESSION['login']['UID'];

        $num['groupNum'] = $data;
        $num['create_by'] = $this->Board_m->searchGroupCreate_by($num['groupNum']);
        $num['UID'] = $userData->UID;

        $this->load->view("/board/boardWriteView", $num);
    }

    function boardWriteSave($data)
    {
        if ($_POST) {

            $title = $_POST['title'];

            $content = $_POST['content'];

            $groupNum = $data;

            $createdate = $this->Board_m->now();

            $bnum = $this->Board_m->boardWriteSave($groupNum, $createdate, $title, $content);

            if($_POST['allcheck'])
            {
                $this->Board_m->updateAllCheck($bnum);
            }

            $list['boardList'] = $this->Board_m->groupBoardList($groupNum);

            $this->load->view("/board/boardList", $list);
        }
    }

    function boardDetailView($data)
    {
        $boardNum = $data;

        $list['detailView'] = $this->Board_m->searchBaordDetail($boardNum);
        $list['groupName'] = $this->Board_m->searchGroupName($list['detailView'][0]->gnum);
        $list['commentView'] = $this->Board_m->searchComment($boardNum);
        $list['hitCount'] = $this->Board_m->updateHitCount($boardNum, $list['detailView'][0]->hit);

        $this->load->view("board/boardDetailView", $list);
    }

    function enrollComment()
    {
        if ($_POST) {
            $commentData = new requestValue();
            $commentData->insertProperty($_POST);

            $createdate = $this->Board_m->now();
            $enrollData = $this->Board_m->enrollComment($commentData, $createdate);

            echo json_encode($enrollData);
        }
    }

}
