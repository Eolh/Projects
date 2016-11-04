<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TagANDPersonal_tag extends CI_Model
{
    /***********************************************************************************************/
    /* START: INSERT                    */
    /***********************************************************************************************/

    function insertTag($tag)
    {
        /* QUERY example
         SELECT COUNT(*) as cnt FROM tag WHERE tagName = '???'
         */
        $this->db->select("COUNT(*) as cnt, tagnum");
        $this->db->from('tag');
        $this->db->where('tagName', $tag);
        /* tag 값이 존재하는지 확인을 하고 값을 입력한다. */

        $result = $this->db->get()->row();

        if($result->cnt == 0) {
            $insertData = array('tagName' => $tag);
            $this->db->insert('tag', $insertData);
            return $this->db->insert_id();
        } else {
            return $result->tagnum;
        }
    }

    function insertUserTag($data)
    {
        $this->db->select("COUNT(*) as cnt");
        $this->db->from('user_tag');
        $this->db->where('tagnum', $data->tagnum);

        $result = $this->db->get()->row();

        if($result->cnt == 0)
        {
            $insertData = array
            (
                'UID'    => $data->UID,
                'tagnum' => $data->tagnum
            );
            $this->db->insert('user_tag', $insertData);
        }
    }

    /***********************************************************************************************/
    /* END:   INSERT                    */
    /***********************************************************************************************/



    /***********************************************************************************************/
    /* START: SELECT                    */
    /***********************************************************************************************/

    function getTagListByUID($data)
    {
        /* QUERY example
        SELECT * FROM personal_tag pt, tag t
        WHERE t.tag_num = pt.tag_num
         */

        $this->db->select("personalNum, ut.tagNum, tagName");
        $this->db->from('user_tag ut');
        $this->db->join('tag t', 't.tagnum = ut.tagnum');
        $this->db->where('UID', $data->UID);

        $result = $this->db->get()->result();

        return $result;
    }

    function getCountFiles($data)
    {
        $this->db->select("COUNT(*) as cnt");
        $this->db->from('file f');
        $this->db->join('user_file uf', 'f.FNum = uf.FNum');
        $this->db->where('UID', $data->UID);

        $result = $this->db->get()->row();

        return $result->cnt;
    }

    function getFileListByUID($data)
    {
        /*
        SELECT * FROM user_file, file,schedule WHERE
        user_file.FNum = file.FNum AND schedule.SdNum = user_file.SdNum
        AND user_file.UID = 1 AND schedule.UID = 1
        ORDER BY `user_file`.`SdNum`  ASC
        */
        $this->db->select("f.FNum, f.FName, f.Flink, f.hashtag, f.size, s.title, s.startTime as date");
        $this->db->from("user_file uf");
        $this->db->join("file f", "uf.FNum = f.FNum");
        $this->db->join("schedule s", "s.SdNum = uf.SdNum AND uf.UID = s.UID");
        $this->db->where("uf.UID", $data->UID);
        /*if($data->search)
            $this->db->like('f.FName', $data->search, 'both');*/
        $this->db->order_by("date", "desc");

        $result = $this->db->get()->result();

        if($result)
            return $result;
        else
            return null;
    }

    function getFileListByUIDAndSearch($data)
    {
        $db = $this->db;

        $db->select("f.FNum, f.FName, f.Flink, f.hashtag, f.size, s.title, s.startTime as date");
        $db->from("user_file uf");
        $db->join("file f", "uf.FNum = f.FNum");
        $db->join("schedule s", "s.SdNum = uf.SdNum AND uf.UID = s.UID");
        $db->where("uf.UID",   $data->UID);
        if($data->tag != "ALL")
            $db->like('f.hashtag', $data->tag,  'both');
        $db->like('f.Flink',   $data->search, 'both');
        $db->order_by("date",  "desc");

        $result = $this->db->get()->result();

        return $result;
    }

    function getFileListByUIDAndTag($data)
    {
        /*
        SELECT * FROM user_file, file,schedule WHERE
        user_file.FNum = file.FNum AND schedule.SdNum = user_file.SdNum
        AND user_file.UID = 1 AND schedule.UID = 1
        ORDER BY `user_file`.`SdNum`  ASC
        */
        $this->db->select("f.FNum, f.FName, f.Flink, f.hashtag, f.size, s.title, s.startTime as date");
        $this->db->from("user_file uf");
        $this->db->join("file f", "uf.FNum = f.FNum");
        $this->db->join("schedule s", "s.SdNum = uf.SdNum AND uf.UID = s.UID");
        $this->db->where("uf.UID", $data->UID);
        $this->db->like('f.hashtag', $data->tag, 'both');
        /*if($data->search)
            $this->db->like('f.FName', $data->search, 'both');*/
        $this->db->order_by("date", "desc");

        $result = $this->db->get()->result();

        if($result)
            return $result;
        else
            return null;
    }

    function getFileListByTag($data)
    {
        $this->db->select("COUNT(*) as cnt");
        $this->db->from('user_file uf');
        $this->db->join('file f', 'f.FNum = uf.FNum');
        $this->db->where('UID', $data->UID);
        $this->db->like('f.hashtag', $data->hashTag, 'both');

        /* before | after | both */

        //getArgDumpDied($this->db->last_query());
        $result = $this->db->get()->row();

        return $result;
    }

    /***********************************************************************************************/
    /* END:   SELECT                    */
    /***********************************************************************************************/
}