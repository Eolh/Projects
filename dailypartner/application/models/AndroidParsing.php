<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-06-05
 * Time: 오후 5:50
 */
class AndroidParsing extends CI_Model
{
    function getAll()
    {
        /* QUERY example
        SELECT * FROM androidParsing
        */
        $this->db->select("*");
        $this->db->from('androidParsing');

        $result = $this->db->get()->result();

        return $result;
    }

    function insertCalendarANDSchedule($data)
    {
        $insertData = array
        (
            "UID" 	    => $data->UID,
            "UCNum"     => $data->UCNum,
            "startTime" => $data->startTime,
            "lastTime"  => $data->endTime,
            "title"     => $data->title,
            "content"   => $data->content
        );

        $this->db->insert("schedule", $insertData);

        return $this->db->insert_id();
    }
}
