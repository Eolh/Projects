<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GroupFile extends CI_Model
{
    /* 2016-08-05 fileUpload logic */
    function getFnum()
    {
        $db = $this->db;

        $db->select("MAX(FNum) as lastNum");
        $db->from("file");

        $result = $db->get()->row();

        return $result->lastNum;
    }

    function insertFile($data)
    {
        $cnt = $data->number;

        $insertData = array (
            'FName'   => $data->userFile['name'] [$cnt],
            'Flink'   => $data->userFile['Flink'][$cnt],
            'hashtag' => $data->hashTag,
            'type'    => $data->userFile['type'] [$cnt],
            'size'    => $data->userFile['size'] [$cnt]
        );

        $this->db->insert('file', $insertData);

        return $this->db->insert_id();
    }

    function insertGroupFile($data)
    {
        $insertData = array (
            'FNum'  => $data->FNum,
            'SdNum' => $data->SdNum,
            'gm_id' => $data->gm_id,
            'date'  => $data->date
        );

        $this->db->insert('group_file', $insertData);
    }

    /* 2016-08-09 */

    function getMemberFileList($data)
    {
        $db = $this->db;

        $db->select("COUNT(*) as cnt");
        $db->from("file f");
        $db->join("group_file gf", "f.Fnum = gf.Fnum");
        $db->where("gf.gm_id", $data->gm_id);

        $result = $db->get()->row();

        return $result;
    }

    function getFileGroupList($data)
    {
        $db = $this->db;

        $db->select("f.FNum, f.FName, f.Flink, f.hashtag, f.size, s.title, s.startTime as date");
        $db->from("group_file gf");
        $db->join("file f", "gf.FNum = f.FNum");
        $db->join("group_schedule s", "s.SdNum = gf.SdNum AND gf.gm_id = s.gm_id");
        $db->where("gf.gm_id", $data->gm_id);
        $db->order_by("date", "desc");

        $result = $db->get()->result();

        //$this->output->enable_profiler(TRUE);
        return $result;
    }

    function getFileGroupListBySearch($data)
    {
        $db = $this->db;

        $db->select("f.FNum, f.FName, f.Flink, f.hashtag, f.size, s.title, s.startTime as date");
        $db->from("group_file gf");
        $db->join("file f", "gf.FNum = f.FNum");
        $db->join("group_schedule s", "s.SdNum = gf.SdNum AND gf.gm_id = s.gm_id");
        $db->where("gf.gm_id", $data->gm_id);
        $db->like('f.Flink', $data->search, 'both');
        $db->order_by("date", "desc");

        $result = $db->get()->result();

        //$this->output->enable_profiler(TRUE);
        // {"search":"tttt","gnum":"1","name":"\uc7a5\uc9c0\ud61c","gm_id":"3"}
        return $result;
    }

    function getFileGroupListToGroup($data)
    {
        $db = $this->db;

        $db->select("f.FNum, f.FName, f.Flink, f.hashtag, f.size, s.title, s.startTime as date");
        $db->from("group_file gf");
        $db->join("file f", "gf.FNum = f.FNum");
        $db->join("group g", "g.gnum = {$data->gnum}");
        $db->join("group_schedule s", "s.SdNum = gf.SdNum AND gf.gm_id = s.gm_id");
        $db->order_by("date", "desc");

        $result = $db->get()->result();

        //$this->output->enable_profiler(TRUE);
        return $result;
    }

    function getFileGroupListToGroupBySearch($data)
    {
        $db = $this->db;

        $db->select("f.FNum, f.FName, f.Flink, f.hashtag, f.size, s.title, s.startTime as date");
        $db->from("group_file gf");
        $db->join("file f", "gf.FNum = f.FNum");
        $db->join("group g", "g.gnum = {$data->gnum}");
        $db->join("group_schedule s", "s.SdNum = gf.SdNum AND gf.gm_id = s.gm_id");
        $db->like('f.Flink', $data->search, 'both');
        $db->order_by("date", "desc");

        $result = $db->get()->result();

        //$this->output->enable_profiler(TRUE);
        return $result;
    }

    function getMemberName($data)
    {
        $db = $this->db;

        $db->select("u.name");
        $db->from("group_member gm");
        $db->join("user u", "gm.uid = u.UID");
        $db->where("gm.gm_id", $data->gm_id);

        $result = $db->get()->row();

        if($result)
            return $result->name;
        else
            return null;
    }

    function getGroupFileList($data)
    {
        $db = $this->db;

        $db->select("*");
        $db->from("group_file gf");
        $db->join("group_member gm", "gf.gm_id = gm.gm_id");
        $db->join("file f", "f.FNum = gf.FNum");
        $db->join("user u", "u.UID = gm.uid");
        $db->where("gnum", $data->gnum);

        $result = $db->get()->result();

        return $result;
    }

    function getGroupFileListByMember($data)
    {
        $db = $this->db;

        $db->select("*");
        $db->from("group_file gf");
        $db->join("group_member gm", "gf.gm_id = gm.gm_id");
        $db->join("file f", "f.FNum = gf.FNum");
        $db->join("user u", "u.UID = gm.uid");
        $db->where("gnum", $data->gnum);
        $db->where("gf.gm_id", $data->gm_id);

        $result = $db->get()->result();

        return $result;
    }
}