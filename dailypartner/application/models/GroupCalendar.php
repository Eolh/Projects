<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupCalendar extends CI_Model
{
    /* 2016-08-05 */
    function getGroup($data)
    {
        $db = $this->db;

        $db->select("g.gnum, gname, create_by, member_count, gm_id, uid");
        $db->from("group g");
        $db->join('group_member gm', 'g.gnum = gm.gnum');
        $db->where("UID", $data->UID);
        $db->where("g.gnum", $data->gnum);

        $result = $db->get()->row();

        return $result;
    }

    function insertGroupCalendarSchedule($data)
    {
        $insertData = array(
            'gm_id'     => $data->gm_id,
            'startTime' => $data->start,
            'lastTime'  => $data->end,
            'title'     => $data->title,
            'content'   => $data->content,
            'firstRank' => $data->firstRank
        );

        $this->db->insert('group_schedule', $insertData);

        return $this->db->insert_id();
    }

    function insertGroupSchedulePlace($data)
    {
        $insertData = array(
            'place_name' => $data->place_name,
            'address' => $data->place_address,
            'latitude' => $data->latitude,
            'longitude' => $data->longitude,
            'SdNum' => $data->SdNum
        );
        $this->db->insert('group_place', $insertData);
        $db= $this->db;

        $sql = "update `group_schedule` (`exsit_place`) VALUES (1) where SdNum = $data->SdNum";

        $this->db->query($sql);
    }


    /* 2016-07-26 */
    function getGroupTabList($data)
    {
        $db = $this->db;

        $db->select("g.gnum, gname, create_by, member_count, gm_id, uid");
        $db->from("group g");
        $db->join('group_member gm', 'g.gnum = gm.gnum');
        $db->where("UID", $data->UID);

        $result = $db->get()->result();

        return $result;
    }

    function getGroupListByGnum($data)
    {
        $db = $this->db;

        $db->select("g.gnum, gname, create_by, member_count, gm_id, uid");
        $db->from("group g");
        $db->join('group_member gm', 'g.gnum = gm.gnum');
        $db->where("UID", $data->UID);
        $db->where("g.gnum", $data->gnum);

        $result = $db->get()->row();

        return $result;
    }

    function getGroupAndMemberList($data, $resultOrRow = true)
    {
        $db = $this->db;

        $db->select('*');
        $db->from('group g');
        $db->join('group_member gm', 'g.gnum = gm.gnum');
        $db->join('user u', 'gm.uid = u.UID');
        $db->where('g.gnum', $data->gnum);

        /* 2016-07-31 added options row or result option is added */
        if($resultOrRow)
            $result = $db->get()->row();
        else
            $result = $db->get()->result();

        return $result;
    }

    /* 2016-07-27 */

    function getGroupCreate_by($data)
    {
        $db = $this->db;

        $db->select("create_by");
        $db->from("group");
        $db->where("create_by", $data->UID);
        $db->where("gnum", $data->gnum);

        $result = $db->get()->row();

        if($result)
            return true;
        else
            return false;
    }

    function getGroupFullEventsByMaster($data) // 팀장용
    {
        $db = $this->db;

        $db->select("g.gnum, gm.uid, gm.gm_id, SdNum, startTime as start, lastTime as end, title, content");
        $db->from("group g");
        $db->join('group_member gm', 'g.gnum = gm.gnum');
        $db->join('group_schedule gs', 'gm.gm_id = gs.gm_id');
        $db->where('g.gnum', $data->gnum);
        $db->order_by('gm.gm_id', 'asc');

        $result = $db->get()->result();

        return $result;
    }

    function getGroupFullEventsByMember($data) // 팀원용
    {
        $db = $this->db;

        $db->select("g.gnum, gm.gm_id, SdNum, startTime as start, lastTime as end, title, content, gm.uid, g.create_by");
        $db->from("group g");
        $db->join('group_member gm', 'g.gnum = gm.gnum AND gm.uid = g.create_by OR gm.uid = '.$data->UID);
        $db->join('group_schedule gs', 'gm.gm_id = gs.gm_id');
        $db->where('g.gnum', $data->gnum);
        $db->order_by('gm.gm_id', 'asc');

        $result = $db->get()->result();

        //$this->output->enable_profiler(TRUE);

        return $result;
    }

    /* 2016-08-01 getMember's Events When member is clicked */

    function getMemberFullEvents($data)
    {
        $db = $this->db;

        $db->select('SdNum, gm.gm_id, startTime as start, lastTime as end, title, content');
        $db->from('group_member gm');
        $db->join('group_schedule gc', 'gm.gm_id = gc.gm_id');
        $db->where('gm.gm_id', $data->gm_id);
        $db->order_by('gm.gm_id', 'asc');

        $result = $db->get()->result();

        //$this->output->enable_profiler(TRUE);

        return $result;
    }

    function updateGroupCalendarEvent($data)
    {
        $updateData = array(
            'startTime' => $data->start,
            'lastTime' => $data->end,
        );

        $this->db->where('SdNum', $data->SdNum);
        $this->db->update('group_schedule', $updateData);
    }

    function getGroupFile($data)
    {
        $this->db->select('f.FNum, f.FName, f.Flink, f.hashtag');
        $this->db->from('file f');
        $this->db->join('group_file uf','uf.FNum = f.FNum');
        $this->db->where('uf.SdNum',$data);

        return $this->db->get()->result();
    }

    function getContent($data)
    {

        $this->db->select("latitude, longitude");
        $this->db->from('group_place sp');
        $this->db->where('SdNum', $data);
        $exist=$this->db->get()->row();

        if(!$exist){
            $this->db->select("content,on/off,result");

            $this->db->from('group_schedule');

            $this->db->where('SdNum', $data);

        }else {
            $this->db->select("content,on/off,result, latitude, longitude");

            $this->db->from('group_schedule s');
            $this->db->join('group_place sp', 's.SdNum = sp.SdNum');

            $this->db->where('s.SdNum', $data);
        }
        return $this->db->get()->row();

    }


    public function importance_all_count($data){
        $sql = "select firstRank,Count(firstRank) as cnt From group_schedule as gs, group_member as gm where gm.gm_id = gs.gm_id and gm.gnum=$data->gnum and ";
        if($data->gm_id!='false')
            $sql.=" gs.gm_id=$data->gm_id and";
        $sql.=" DATE(gs.lastTime) = CURDATE() group by firstRank order by firstRank desc";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    public function importance_count($data){
        $sql = "select firstRank,Count(firstRank) as cnt From group_schedule as gs, group_member as gm where gm.gm_id = gs.gm_id and gm.gnum=$data->gnum and ";
        if($data->gm_id!='false')
            $sql.=" gs.gm_id=$data->gm_id and ";
        $sql.="  `on/off` =1 and DATE(gs.lastTime) = CURDATE() group by firstRank order by firstRank desc";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    public function date_all_count($data){
        $sql = "select DATE(lastTime) as day,Count(lastTime) as cnt From group_schedule as gs, group_member as gm where gm.gm_id = gs.gm_id and gm.gnum=$data->gnum  ";
        if($data->gm_id!='false')
            $sql.=" and gs.gm_id=$data->gm_id ";
        $sql.=" group by DATE(lastTime) order by DATE(lastTime) desc limit 7;";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    public function date_count($data){
        $sql = "select DATE(lastTime) as day,Count(lastTime) as cnt From group_schedule as gs, group_member as gm where gm.gm_id = gs.gm_id and gm.gnum=$data->gnum and ";
        if($data->gm_id!='false')
            $sql.="gs.gm_id=$data->gm_id and ";
        $sql.=" `on/off`=1 group by DATE(lastTime) order by DATE(lastTime) desc limit 7;";
        $query = $this->db->query($sql);
        $result = $query->result();

        return $result;
    }

    public function get_gm_id($data){
        $sql = "select gm_id  from group_member where uid = $data->uid and gnum = $data->gnum";
        $query =$this->db->query($sql);
        $result = $query->row();

        return $result->gm_id;
    }

    public function checked($data){
        $sql = "update `group_schedule` set (`on/off`)=case `on/off` WHEN 1 THEN 0 WHEN 0 THEN 1 where SdNum = $data->SdNum";
        $query =$this->db->query($sql);
    }
}