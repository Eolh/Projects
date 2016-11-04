<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2016-04-19
 * Time: 오전 9:27
 */
class maps_m extends CI_Model
{
    function get_list()
    {
        $sql = "select * from items";

        $query = $this->db->query($sql);

        $result = $query->result();

        return $result;
    }

    function insert_maps($data)
    {
        $sql = "INSERT INTO schedule_place(address, latitude, longitude) VALUES ('" . $data['address'] . "','" . $data['lat'] . "','" . $data['lng'] . "')";
        $this->db->query($sql);
    }

    function load_schedule($data)
    {

        $db = $this->db;
        $db->select("s.UID, CAST(s.startTime as TIME) as startTime, CAST(s.lastTime as TIME) as lastTime, s.title as title, s.content as content, sp.place_name as place_name, sp.latitude as latitude, sp.longitude as longitude");
        $db->from('schedule s');
        $db->join('schedule_place sp','s.SdNum = sp.SdNum');
        $db->where('s.UID',$data->UID);
        $db->where('CAST(s.startTime as DATE)=',$data->date);
        $db->order_by('startTime',"asc");

        $result = $db->get()->result();

        return $result;

    }

    function load_places($data)
    {
        $db = $this->db;
        $db->select("CAST(s.startTime as TIME) as startTime, CAST(s.lastTime as TIME) as lastTime, s.title as title, s.content as content, sp.place_name as place_name, sp.latitude as latitude, sp.longitude as longitude");
        $db->from("schedule s");
        $db->join("schedule_place sp", "s.SdNum = sp.SdNum");
        $db->where("s.UID",$data->UID);
        $db->where("CAST(s.startTime as DATE)=",$data->date);
        $db->order_by("startTime","asc");

        $result = $db->get()->result();

        return $result;
    }

    function group_members($data){

        $db = $this->db;
        $db->select("u.uid, u.name, gm.gm_id,u.id");
        $db->from("group_member gm");
        $db->join("user u", "gm.uid = u.uid");
        $db->where("gnum",$data);

        $result = $db->get()->result();

        return $result;

    }

    function group_schedule($data){

        $db = $this->db;
        $db->select("CAST(gs.startTime as TIME) as startTime, CAST(gs.lastTime as TIME) as lastTime, gs.title as title, gs.content as content,gs.gm_id,gs.exsit_place,gs.SdNum,u.name,u.id");
        $db->from("group_schedule gs");
        $db->join("group_member gm","gm.gm_id=gs.gm_id");
        $db->join("user u","u.uid=gm.uid");
        $db->where("gm.gnum",$data->gnum);
        $db->where("CAST(gs.startTime as DATE)=",$data->date);

        $result = $db->get()->result();

        return $result;

    }

    function get_member_info($data){
        $db= $this->db;
        $db->select("id");
        $db->from("user u");
        $db->join("group_member gm","u.uid=gm.uid");
        $db->where("gm.gnum",$data->gnum);

        $result = $db->get()->result();

        return $result;
    }

    function group_place($data){
        $db = $this->db;
        $db->select("place_name,latitude,longitude");
        $db->from("group_place");
        $db->where("SdNum",$data->SdNum);
        $result = $db->get()->result();

        return $result;
    }

    function getGroupName($data){
        $db= $this->db;
        $db->select("gname");
        $db->from("balance.group");
        $db->where("gnum",$data);

        $result = $db->get()->row_array(1);

        return $result['gname'];
    }

}