<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 로그인이 완료되면 대시보드로 이동한다.
 * 대시보드에 관한 CTL
 */
class Main extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        /* start of database */
        $this->load->database();
        $this->load->model("Group");
        /* end of database */

        ///////////////////////////////////////////////////////////////////

        /*$cal['template'] = array
        (
            'table_open'				=> '<table border="0" cellpadding="4" cellspacing="0">',
            'heading_row_start'			=> '<tr>',
            'heading_previous_cell'		=> '<th><a href="{previous_url}">&lt;&lt;</a></th>',
            'heading_title_cell'		=> '<th colspan="{colspan}">{heading}</th>',
            'heading_next_cell'			=> '<th><a href="{next_url}">&gt;&gt;</a></th>',
            'heading_row_end'			=> '</tr>',
            'week_row_start'			=> '<tr>',
            'week_day_cell'				=> '<td>{week_day}</td>',
            'week_row_end'				=> '</tr>',
            'cal_row_start'				=> '<tr>',
            'cal_cell_start'			=> '<td>',
            'cal_cell_start_today'		=> '<td>',
            'cal_cell_start_other'		=> '<td style="color: #666;">',
            'cal_cell_content'			=> '<a href="{content}">{day}</a>',
            'cal_cell_content_today'	=> '<a href="{content}"><strong>{day}</strong></a>',
            'cal_cell_no_content'		=> '{day}',
            'cal_cell_no_content_today'	=> '<strong>{day}</strong>',
            'cal_cell_blank'			=> '&nbsp;',
            'cal_cell_other'			=> '{day}',
            'cal_cell_end'				=> '</td>',
            'cal_cell_end_today'		=> '</td>',
            'cal_cell_end_other'		=> '</td>',
            'cal_row_end'				=> '</tr>',
            'table_close'				=> '</table>'
        );*/

        /* start of library */
        $this->load->library('calendar'/*, $cal*/);
        $this->load->library("error_reporter");
        $this->load->library("requestValue");
        /* end of library */
    }

    public function index()
    {
        /*$_SESSION['login']['ID']  = "foway";
        $_SESSION['login']['UID'] = "1";*/

        /* start: modelHandler */
        $dashData = new requestValue();
        $dashData->UID = 1;
        /* end:   modelHandler */

        $data['cal'] = $this->calendar->generate();
        $data['groupInfo'] = $this->Group->getGroupList($dashData);

        $this->load->view("/main/dashboard", $data);
    }
}