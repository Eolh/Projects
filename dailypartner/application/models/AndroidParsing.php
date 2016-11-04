<?php

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

    function getAutoList($data)
    {
        $db = $this->db;
        $db->select("*");
        $db->from("androidParsing");
        $db->where("UID", $data->UID);

        $result = $this->db->get()->result();

        return $result;
    }

    function getCalendarName($data)
    {
        $db = $this->db;

        $db->select("calName");
        $db->from("calendar c");
        $db->join("user_calendar uc", "c.calNum = uc.calNum");
        $db->where("uc.UCNum", $data->UCNum);

        $result = $this->db->get()->row();

        return $result->calName;
    }

    function insertParsing($data)
    {
        $insertData = array
        (
            "UID" 	    => $data->UID,
            "UCNum"     => $data->multiCalendar,
            "phoneNum" => $data->phoneNumber,
            "keyword"  => $data->keyWord,
        );

        $this->db->insert("androidParsing", $insertData);

        return $this->db->insert_id();
    }

    function getParsing($data)
    {
        $db = $this->db;

        $db->select("*");
        $db->from("androidParsing");
        $db->where("apNum", $data);

        $result = $this->db->get()->row();

        return $result;
    }
}