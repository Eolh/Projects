<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Board_m extends CI_Model
{
    function addGroupBoard($data,$curdate)
    {
        $sql1 = "SELECT gnum FROM balance.group WHERE gname = '{$data->gname}'";

        $re = $this->db->query($sql1)->row();

        $sql2 = "SELECT ID FROM balance.user WHERE UID = '{$_SESSION['login']['UID']}'";

        $re2 = $this->db->query($sql2)->row();

        $sql3 = "INSERT INTO `group_board` (`bnum`, `gnum`, `title`, `content`, `create_by`, `hit`, `create_date`) VALUES (NULL, '$re->gnum', '$data->gname 그룹 게시판 입니다.', '그룹원과의 소통 중심 게시판 입니다.', '$re2->ID', '0', '$curdate->date')";

        $this->db->query($sql3);

    }

    function groupBoardList($groupNum)
    {
        $sql = "SELECT * FROM group_board WHERE gnum = $groupNum ORDER BY all_check DESC, create_date DESC";

        $result = $this->db->query($sql)->result();

        return $result;
    }

    function Now()
    {
        $sql = "SELECT NOW() as date";

        $result = $this->db->query($sql)->row();

        return $result;
    }

    function boardWriteSave($groupNum, $createdate, $title, $content)
    {
        $sql1 = "SELECT ID FROM balance.user WHERE UID = '{$_SESSION['login']['UID']}'";

        $re = $this->db->query($sql1)->row();

        $sql2 = "INSERT INTO `group_board` (`bnum`, `gnum`, `title`, `content`, `create_by`, `hit`, `create_date`) VALUES (NULL, '$groupNum', '$title', '$content', '$re->ID', '0', '$createdate->date')";

        $this->db->query($sql2);

        $result = $this->db->insert_id();

        return $result;

    }
    function updateAllCheck($data)
    {
        $sql = "UPDATE `group_board` SET `all_check` = '1' WHERE `group_board`.`bnum` = $data";

        $this->db->query($sql);

    }
    function searchBaordDetail($data)
    {
        $sql = "SELECT * FROM group_board WHERE bnum = '$data'";

        $result = $this->db->query($sql)->result();

        return $result;
    }

    function searchGroupName($data)
    {
        $sql = "SELECT gname FROM balance.group WHERE gnum = $data";

        $result= $this->db->query($sql)->row();

        return $result;
    }

    function searchGroupCreate_by($data)
    {
        $sql = "SELECT create_by FROM balance.group WHERE gnum = $data";

        $result= $this->db->query($sql)->row();

        return $result;
    }

    function searchComment($data)
    {
        $sql = "SELECT * FROM group_comment WHERE bnum = $data";

        $result = $this->db->query($sql)->result();

        return $result;
    }

    function enrollComment($data, $createdate)
    {

        $sql1 = "SELECT ID FROM balance.user WHERE UID = '{$_SESSION['login']['UID']}'";

        $re = $this->db->query($sql1)->row();

        $sql2 = "INSERT INTO `group_comment` (`cnum`, `bnum`, `content`, `create_by`, `create_date`) VALUES (NULL, '$data->bnum', '$data->content', '$re->ID', '$createdate->date');";

        $this->db->query($sql2);

        $cnum = $this->db->insert_id();

        $sql3 = "SELECT * FROM group_comment WHERE cnum = $cnum";

        $result = $this->db->query($sql3)->result();

        return $result;

    }

    function updateHitCount($data, $hit)
    {
        $sql = "UPDATE `group_board` SET `hit` = '$hit'+1 WHERE `group_board`.`bnum` = $data";

        $this->db->query($sql);

        $hitcount = $hit + 1;

        return $hitcount;
    }

    function searchbyUID($UID)
    {

    }
}
