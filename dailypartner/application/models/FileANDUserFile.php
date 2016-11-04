<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FileANDUserFile extends CI_Model
{
    /***********************************************************************************************/
    /* START: INSERT                    */
    /***********************************************************************************************/

    function insertFile($data)
    {
        /* QUERY example
         * INSERT INTO file (`FNum`, `FName`, `hashtag`, `type`, `size`) VALUES (NULL, 'SS', 'SDS;D;SDA;S', 'SS', '23124')
         * */

        $cnt = $data->number;

        $insertData = array
        (
            'FName'   => $data->userFile['name'][$cnt],
            'Flink'   => $data->userFile['Flink'][$cnt],
            'hashtag' => $data->hashTag,
            'type'    => $data->userFile['type'][$cnt],
            'size'    => $data->userFile['size'][$cnt]
        );

        $this->db->insert('file', $insertData);

        return $this->db->insert_id();
    }

    function insertUserFile($data)
    {
        $insertData = array
        (
            'FNum'  => $data->FNum,
            'SdNum' => $data->SdNum,
            'UID'   => $data->UID,
            'date'  => $data->date
        );

        $this->db->insert('user_file', $insertData);
    }

    /***********************************************************************************************/
    /* END:   INSERT                    */
    /***********************************************************************************************/


    function getFNum()
    {
        /* QUERY example
        SELECT c.calNum, calName FROM calendar c, user_calendar uc
        WHERE c.calNum = uc.calNum AND UID = 1
         */

        $this->db->select("MAX(FNum) as lastNum");
        $this->db->from('file');

        $result = $this->db->get()->row();

        return $result->lastNum;
    }

    function getScheduleFile($data)
    {
        $this->db->select('f.FNum, f.FName, f.Flink, f.hashtag');
        $this->db->from('file f');
        $this->db->join('user_file uf','uf.FNum = f.FNum');
        $this->db->where('uf.SdNum',$data);

        return $this->db->get()->result();
    }

    function getGroupFile($data)
    {
        $this->db->select('f.FNum, f.FName, f.Flink, f.hashtag');
        $this->db->from('file f');
        $this->db->join('group_file uf','uf.FNum = f.FNum');
        $this->db->where('uf.SdNum',$data);

        return $this->db->get()->result();
    }

    function getFileList($data)
    {
        /* QUERY example
        SELECT DISTINCT (substring(startTime, 1, 10)) as start, 'img' FROM schedule, file, user_file
        WHERE schedule.UID = user_file.UID AND file.FNum = user_file.FNum
        AND user_file.SdNum = schedule.SdNum
         */
        $db = $this->db;

        $db->select("DISTINCT(substring(startTime, 1, 10)) as start, 'img', COUNT(FNum) as cnt");
        $db->from("user_file uf");
        $db->join("schedule s", "s.UID = uf.UID AND uf.SdNum = s.SdNum");
        $db->where("uf.UID", $data->UID);
        $db->group_by("substring(startTime, 1, 10)");
        $db->order_by("substring(startTime, 1, 10)");

        $result = $db->get()->result();

        return $result;
    }

    function getFileListBySdNum($data)
    {
        /* QUERY example
        SELECT * FROM file, user_file
        WHERE SdNum = 108 AND file.FNum = user_file.FNum AND user_file.UID = 1
        */

        $db = $this->db;

        $db->select("f.FName, f.Flink, f.hashtag");
        $db->from("file f");
        $db->join("user_file uf", "uf.FNum = f.FNum");
        $db->where("uf.UID", $data->UID);
        $db->where("SdNum", $data->SdNum);

        $result = $db->get()->result();

        return $result;
    }

    function getFileListByDate($data)
    {
        /* QUERY example
         SELECT FName, Flink, hashtag FROM file f, user_file uf, schedule s
         WHERE f.FNum = uf.FNumAND s.UID = uf.UID AND uf.SdNum = s.SdNum
         AND uf.UID = 1 AND s.startTime LIKE '%2016-06-26%'
         */

        $db = $this->db;

        $db->select('f.FName, f.Flink, f.hashtag');
        $db->from('file f');
        $db->join('user_file uf','uf.FNum = f.FNum');
        $db->join('schedule s', 's.UID = uf.UID AND uf.SdNum = s.SdNum');
        $db->where('uf.UID', $data->UID);
        $db->like("s.startTime", $data->date, "both");
        $db->order_by("startTime");

        $result = $db->get()->result();

        return $result;
    }
}