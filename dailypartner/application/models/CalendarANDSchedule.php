<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CalendarANDSchedule extends CI_Model
{
    function getDateList($data)
    {
        /* QUERY example
         SELECT substring(startTime, 1, 10) FROM schedule WHERE UID = 1 AND UCNum = 1 group by startTime
         */

        $db = $this->db;
        $db->select("DISTINCT(substring(startTime, 1, 10)) as date");
        $db->from("schedule");
        $db->where("UID", $data->UID);
        //$db->where("UCNum", $data->UCNum);
        $db->order_by("startTime");

        $result = $db->get()->result();

        return $result;
    }

    function getCalendarANDScheduleToMonth($data)
    {
        /* QUERY example
        SELECT * FROM user_calendar, schedule
        WHERE user_calendar.UID = schedule.UID AND user_calendar.UCNum = schedule.UCNum
        AND user_calendar.calNum = 1 AND user_calendar.UID = 1
        */

        $this->db->select("SdNum, startTime as start, lastTime as end , title, uc.UCNum, CASE s.firstRank WHEN '1' THEN 'info' WHEN '2' THEN 'important' WHEN '3' THEN 'success' ELSE 'chill' END as className");
        $this->db->from('user_calendar uc');
        $this->db->join('schedule s', 'uc.UID = s.UID AND uc.UCNum = s.UCNum');
        $this->db->where('uc.UID', $data->UID);
        if ($data->calNum != 1)
            $this->db->where('uc.calNum', $data->calNum);
        /* calNum is Default Calendar */

        $result = $this->db->get()->result();

        return $result;
    }

    function insertUserCalendar($data)
    {
        $insertData = array
        (
            'UID' => $data->UID,
            'calNum' => $data->calNum
        );
        $this->db->insert('user_calendar', $insertData);
        if ($UCNum = $this->db->insert_id()) {
            $this->db->select("c.calName , UCNum, UID");
            $this->db->from('user_calendar uc');
            $this->db->join('calendar c', 'uc.calNum = c.calNum');
            $this->db->where('UID', $data->UID);
            $this->db->where('UCNum', $UCNum);

            return $this->db->get()->row();
        }
    }

    function insertCalendarANDSchedule($data)
    {
        /* QUERY example
        INSERT INTO schedule
        (`UID`, `UCNum`, `startTime`, `lastTime`, `title`, `content`) VALUES
        ('1', '1', '2016-05-18 07:00:00', '2016-05-19 10:00:00', 'aa', 'aa')
        */

        $insertData = array
        (
            'UID' => $data->UID,
            'UCNum' => $data->UCNum,
            'startTime' => $data->start,
            'lastTime' => $data->end,
            'title' => $data->title,
            'content' => $data->content,
            'firstRank' => $data->color
        );

        $this->db->insert('schedule', $insertData);

        return $this->db->insert_id();
    }

    function insertSchedulePlace($data, $id)
    {
        $insertData = array(
            'place_name' => $data->place_name,
            'address' => $data->place_address,
            'latitude' => $data->latitude,
            'longitude' => $data->longitude,
            'SdNum' => $id,

        );
        $this->db->insert('schedule_place', $insertData);
    }

    function updateCalendarANDSchedule($data)
    {
        /* QUERY example
        UPDATE schedule SET `startTime` = '2016-05-13 06:00:00', `lastTime` = '2016-05-16 08:00:00', `title` = 'QWEQRWQ', `content` = 'QWEWERWE'
        WHERE `schedule`.`SdNum` = 3
        */

        $updateData = array(

            'startTime' => $data->start,
            'lastTime' => $data->end,
            'title' => $data->title
        );

        $this->db->where('SdNum', $data->SdNum);
        $this->db->update('schedule', $updateData);
    }


    function getContent($data)
    {

        $this->db->select("latitude, longitude");
        $this->db->from('schedule_place sp');
        $this->db->where('SdNum', $data);
        $exist=$this->db->get()->row();

        if(!$exist){
            $this->db->select("content");

            $this->db->from('schedule');

            $this->db->where('SdNum', $data);

        }else {
            $this->db->select("content, latitude, longitude");

            $this->db->from('schedule s');
            $this->db->join('schedule_place sp', 's.SdNum = sp.SdNum');

            $this->db->where('s.SdNum', $data);
        }
        return $this->db->get()->row();

    }

    function getScheduleWithDate($data)
    {
        $this->db->select("startTime, lastTime");
        $this->db->from("schedule");
        $this->db->where("UID", $data->UID);
        $this->db->where("DATE(startTime)", $data->date);
        $this->db->or_where("DATE(lastTime)", $data->date);

        return $this->db->get()->result();
    }

    function getBriefing($data)
    {
        $db = $this->db;

        $db->select("s.title as title, CAST(s.startTime as TIME) as startTime, sp.place_name as place_name");
        $db->from('schedule s');
        $db->join('schedule_place sp', 's.SdNum = sp.SdNum');
        $db->where("s.startTime >=", "now()", FALSE);
        $db->where("s.startTime <", "CURDATE() + INTERVAL 1 DAY", FALSE);
        $db->where("s.UID ", $data->UID);
        $db->order_by("startTime", "asc");
        $db->limit(2);

        $result = $db->get()->result();

        return $result;

    }

    function getBriefingCount($data)
    {
        $db = $this->db;
        $db->select("COUNT(*) as leftSche");
        $db->from('schedule');
        $db->where("startTime >=", "now()", FALSE);
        $db->where("startTime <", "CURDATE() + INTERVAL 1 DAY", FALSE);
        $db->where("UID ", $data->UID, FALSE);

        $result[0] = $db->get()->row();

        $db->select("COUNT(*) as countSche");
        $db->from('schedule');
        $db->where("CAST(startTime as DATE) =", "CURDATE()", FALSE);
        $db->where("UID ", $data->UID, FALSE);

        $result[1] = $db->get()->row();

        return $result;

    }


}