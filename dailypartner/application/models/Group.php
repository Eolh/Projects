<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends CI_Model
{
    function insertGroup($data)
    {
        /* QUERY example
        SELECT COUNT(*) as cnt, calNum FROM calendar WHERE calName = '???'
         */
        $this->db->select("COUNT(*) as cnt, calNum");
        $this->db->from('calendar');
        $this->db->where('calName', $data->groupName);
        /* tag 값이 존재하는지 확인을 하고 값을 입력한다. */

        $result = $this->db->get()->row();

        if($result->cnt == 0) {
            $insertData = array
            (
                'calName' => $data->groupName,
                'create_day' => $data->date
            );
            $this->db->insert('calendar', $insertData);
            $data->calNum = $this->db->insert_id();
            $data->check = true;
        } else {
            $data->calNum = $result->calNum;
            $data->check = false;
        }
        return $data;
    }

    function getGroupList($data)
    {
        /* QUERY example
        SELECT c.calNum, calName FROM calendar c, user_calendar uc
        WHERE c.calNum = uc.calNum AND UID = 1
         */

        $this->db->select("c.calNum, calName, UCNum");
        $this->db->from('calendar c');
        $this->db->join('user_calendar uc', 'c.calNum = uc.calNum');
        $this->db->where('UID',$data->UID);

        $result = $this->db->get()->result();

        return $result;
    }

    function getCalNumByUCNum($data)
    {
        /* QUERY example

        */
        $db = $this->db;

        $db->select("calNum");
        $db->from("user_calendar");
        $db->where("UID", $data->UID);
        $db->where("UCNum", $data->UCNum);

        $result = $db->get()->row();

        return $result->calNum;
    }

    function getUserFriend($data){

        $sql = "select UID,ID from user where UID in (select if(friend1 = '$data->UID' , friend2, friend1) as friend from friend where (friend1 = '$data->UID' OR friend2 = '$data->UID') and status =1)";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }


}