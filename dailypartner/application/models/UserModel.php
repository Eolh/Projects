<?php

class UserModel extends CI_Model
{
    function userSearchByName($data)
    {
        $sql = "SELECT * FROM user WHERE name = '{$data->name}'";

        $result = $this->db->query($sql)->result();

        return $result;
    }

    function friendadd($data)
    {

        $sql1 = "SELECT ID FROM user WHERE UID = '{$data->friend2}'";

        $result = $this->db->query($sql1)->result();

        $sql2 = "INSERT INTO `friend` (`friend_num`, `friend1`, `friend2`, `status`) VALUES (NULL, '{$_SESSION['login']['UID']}', '{$data->friend2}', '0')";

        $this->db->query($sql2);

        $last = $this->db->insert_id();

        $array = array($result, $last);

        return $array;
    }

    function userSearchByID($data)
    {
        $sql = "SELECT ID FROM user WHERE UID = $data->friend2";

        $result = $this->db->query($sql)->result();

        return $result;
    }

    function frienddelete($data)
    {
        $sql1 = "SELECT friend2 FROM friend WHERE friend_num = $data->friend_num";

        $result = $this->db->query($sql1)->result();

        $sql2 = "DELETE FROM friend WHERE friend_num = $data->friend_num";

        $this->db->query($sql2);

        return $result;
    }

    function friendaddlistAdd($data)
    {
        $sql1 = "UPDATE `friend` SET `status` = '1' WHERE friend1 = '$data->friend1' and friend2 = '{$_SESSION['login']['UID']}'";

        $this->db->query($sql1);

        $sql2 = "INSERT INTO `friend` (`friend_num`, `friend1`, `friend2`, `status`) VALUES (NULL, '{$_SESSION['login']['UID']}', '{$data->friend1}', '1')";

        $this->db->query($sql2);

        $sql3 = "SELECT * FROM user WHERE UID = $data->friend1";

        $result = $this->db->query($sql3)->result();

        return $result;

    }

    function friendaddlistDelete($data)
    {
        $sql1 = "SELECT friend_num FROM friend WHERE friend1 = '$data->friend1' and friend2 = '{$_SESSION['login']['UID']}' and status = 0";

        $re = $this->db->query($sql1)->row();

        $sql2 = "DELETE FROM friend WHERE friend_num = '$re->friend_num'";

        $this->db->query($sql2);

    }

    function groupadd($data, $curdate)
    {
        $sql2 = "INSERT INTO balance.group (`gnum`, `gname`, `create_by`, `create_date`) VALUES (NULL, '$data->gname', '{$_SESSION['login']['UID']}', '$curdate->date')";

        $this->db->query($sql2);

        $gnum = $this->db->insert_id();

        $sql3 = "INSERT INTO `group_member` (`gm_id`, `gnum`, `uid`) VALUES (NULL, '$gnum', '{$_SESSION['login']['UID']}')";

        $this->db->query($sql3);

        $sql4 = "SELECT gname,gnum FROM balance.group WHERE gnum = $gnum";

        $result = $this->db->query($sql4)->result();

        return $result;
    }

    function SearchMember($data)
    {
        $sql = "SELECT uid FROM group_member WHERE gnum = '$data->gnum'";

        $result = $this->db->query($sql)->result();

        return $result;
    }

    function groupMemberList($data)
    {
        $sql = "SELECT * FROM user WHERE UID = $data->uid";

        $result = $this->db->query($sql)->result();

        return $result;
    }

    function checkMemberIngroup($data)
    {
        $sql1 = "SELECT uid FROM group_member WHERE gnum = '$data->gnum' and uid = $data->UID";

        $result = $this->db->query($sql1)->result();

        return $result;
    }

    function addMemberinGroup($data)
    {
        $sql1 = "INSERT INTO `group_member` (`gm_id`, `gnum`, `uid`) VALUES (NULL, '$data->gnum', '$data->UID')";

        $this->db->query($sql1);

        $sql2 = "SELECT member_count FROM balance.group WHERE gnum = '$data->gnum'";

        $re = $this->db->query($sql2)->row();

        $sql3 = "UPDATE balance.group SET `member_count` = $re->member_count + 1  WHERE gnum = $data->gnum;";

        $this->db->query($sql3);

        $sql4 = "SELECT * FROM user WHERE UID = $data->UID";

        $result = $this->db->query($sql4)->result();

        return $result;
    }
}
