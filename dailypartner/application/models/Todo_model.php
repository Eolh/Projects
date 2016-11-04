<?php

class Todo_model extends CI_Model
{
    function todolist(){

        $sql = "SELECT * FROM todo WHERE start_day = CURRENT_DATE ORDER  BY  work_num DESC";

        $re = $this->db->query($sql)->result();

        return $re;
    }

    function alltodolist(){

        $sql = "SELECT * FROM todo";

        $re = $this->db->query($sql)->result();

        return $re;
    }

    function curdate(){
        $sql = "SELECT CURDATE() as date";

        $re = $this->db->query($sql)->row();

        return $re;
    }

    function date($selectData){

        $sql = "SELECT start_day FROM todo WHERE start_day = '$selectData->start_day'";

        $re = $this->db->query($sql)->row();

        return $re;
    }

    function dateintodo(){

        $sql = "SELECT DISTINCT start_day from todo ORDER BY start_day ASC";

        $re = $this->db->query($sql)->result();

        return $re;
    }

    function deletetodo($deletedata){

        $sql = "Delete from todo where work_num = $deletedata->work_num";
        $this->db->query($sql);
    }

    function inserttodo($insertdata){

        $sql = "INSERT INTO `todo` (`work_num`, `UID`, `work`, `start_day`, `confirm_check`, `extend_num`) VALUES (NULL, '$insertdata->UID', '$insertdata->work', '$insertdata->start_day' , '0', '0');";
        $this->db->query($sql);

        $last = $this->db->insert_id();

        return $last;

    }

    function selecttodo($worknum){

        $sql = "SELECT * FROM todo WHERE work_num = $worknum";

        $re = $this->db->query($sql)->row();

        return $re;
    }

    function selecttodoindate($selectData){

        $sql = "SELECT * FROM todo WHERE start_day = '$selectData->start_day'";

        $re = $this->db->query($sql)->result();

        return $re;
    }

    function modifytodo($modifydata){

        $sql = "UPDATE `todo` SET `work` = '$modifydata->work', `extend_num` = extend_num +1 WHERE `todo`.`work_num` = $modifydata->work_num;";

        $this->db->query($sql);
    }

    function checktodo($checkdata){

        $sql = "UPDATE `todo` SET `confirm_check` = 1 WHERE `todo`.`work_num` = $checkdata->work_num";

        $this->db->query($sql);
    }

    function notchecktodo($checkdata){

        $sql = "UPDATE `todo` SET `confirm_check` = 0 WHERE `todo`.`work_num` = $checkdata->work_num";

        $this->db->query($sql);
    }

    function getTodoCount($data){
        $db = $this->db;
        $db->select("COUNT(*) as leftTodo");
        $db->from("todo");
        $db->where("UID",$data->UID);
        $db->where("start_day","CURDATE()",FALSE);

        $result = $db->get()->row();

        return $result;
    }

    function enrollResult($data){

        $sql = "UPDATE `todo` SET `result` = '$data->result' WHERE `todo`.`work_num` = $data->work_num";

        $this->db->query($sql);
    }

    function selectResult($data){

        $sql = "SELECT result From todo WHERE work_num = $data";

        $result = $this->db->query($sql)->row();

        return $result;
    }
}
?>