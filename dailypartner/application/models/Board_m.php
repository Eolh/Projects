<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Board_m extends CI_Model
{
    function addGroupBoard($data, $curdate)
    {
        $sql1 = "SELECT gnum FROM balance.group WHERE gname = '{$data->gname}'";

        $re = $this->db->query($sql1)->row();

        $sql2 = "SELECT ID FROM balance.user WHERE UID = '{$_SESSION['login']['UID']}'";

        $re2 = $this->db->query($sql2)->row();

        $sql3 = "INSERT INTO `group_board` (`bnum`, `gnum`, `title`, `content`, `create_by`, `hit`, `create_date`) VALUES (NULL, '$re->gnum', '$data->gname グループ掲示板です。', 'グループメンバーとのコミュニケーションができます。', '$re2->ID', '0', '$curdate->date')";

        $this->db->query($sql3);

    }

    function groupBoardList($groupNum)
    {
        $sql = "SELECT * FROM group_board WHERE gnum = $groupNum ORDER BY all_check DESC, create_date DESC";

        $result = $this->db->query($sql)->result();

        return $result;
    }

    function groupBoardAllCheck($data)
    {
        $sql = "SELECT * FROM group_board WHERE gnum = $data->gnum and all_check = 1 ORDER BY create_date DESC limit 0,3";

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

        $result = $this->db->query($sql)->row();

        return $result;
    }

    function searchGroupCreate_by($data)
    {
        $sql = "SELECT create_by FROM balance.group WHERE gnum = $data";

        $result = $this->db->query($sql)->row();

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

    function deleteComment($cnum)
    {
        $sql = "DELETE From group_comment WHERE cnum = $cnum";

        $this->db->query($sql);
    }

    function SearchIDbyUID($UID)
    {
        $sql = "SELECT ID FROM balance.user WHERE UID = $UID";

        $result = $this->db->query($sql)->row();

        return $result;
    }

    function deleteBoard($bnum)
    {
        $sql1 = "DELETE From group_comment WHERE bnum = $bnum";

        $this->db->query($sql1);

        $sql2 = "DELETE From group_board WHERE bnum = $bnum";

        $this->db->query($sql2);
    }

    function moveContentBySdNum($SdNum)
    {
        $sql = "SELECT * FROM group_schedule WHERE SdNum = $SdNum";

        $result = $this->db->query($sql)->result();

        return $result;
    }
    function SearchUIDbygm_id($gm_id)
    {
        $sql = "SELECT UID FROM group_member WHERE gm_id = $gm_id";

        $result = $this->db->query($sql)->row();

        return $result;
    }

    function scheduleMoveEnroll($moveContent,$moveData,$createdate,$ID)
    {
        $sql1 = "SELECT ID FROM balance.user WHERE UID = '{$_SESSION['login']['UID']}'";

        $userID = $this->db->query($sql1)->row();

        $sql2 = "INSERT INTO `group_board` (`bnum`, `gnum`, `title`, `content`, `create_by`, `hit`, `create_date`) VALUES (NULL, '$moveData->gnum', '[仕事変更、$ID->ID] $moveContent->title', ' $moveContent->title($moveData->start ~ $moveData->end) -> $moveContent->startTime ~ $moveContent->lastTime で変更されました。', '$userID->ID', '0', '$createdate->date')";

        $this->db->query($sql2);
    }
}
