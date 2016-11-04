<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: SAMSUNG
 * Date: 2016-07-21
 * Time: 오후 10:42
 */
class FriendANDGroup extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getUserFriend($data)
    { /*friend list*/

//        $select = "if (friend1 = '$data->UID', friend2, friend1) as friend";
//        $this->db->select($select);
//        $this->db->from('friend');
//        $where  = ("(friend1 = '$data->UID' OR friend2 = '$data->UID' )");
//        $this->db->where($where)
//                 ->where('status',1);

        /*view) freind info : profile, name, ID, Tel*/
        $sql = "select UID,ID,name,Tel from balance.user where UID in (select if(friend1 = '$data->UID' , friend2, friend1) as friend from friend where (friend1 = '$data->UID' OR friend2 = '$data->UID') and status =1)";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    function FriendRequest($data)
    {
        $sql1 = "select friend1 from friend where friend2 = $data->UID and status=0";

        $re = $this->db->query($sql1)->row();

        if ($re) {
            $sql2 = "select * from balance.user where UID = '$re->friend1'";

            $result = $this->db->query($sql2)->result();

            return $result;
        } else
            return 0;
    }

    function getUserGroupNum($data)
    {
        $sql1 = "select gnum from group_member where uid = $data->UID";

        $result = $this->db->query($sql1)->result();

        return $result;
    }

    function getUserGroupList($data)
    {
        $sql = "select * from balance.group where gnum = $data->gnum";

        $result = $this->db->query($sql)->result();

        return $result;
    }

    function getGroupName($data){
        $sql="select gname from group where gnum = $data";
        $result = $this->db->query($sql)->result();

        return $result;
    }
}
?>