<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CalendarANDSchedule extends CI_Model
{
    function getCalendarANDScheduleToMonth($data)
    {
        /* QUERY example
        SELECT * FROM user_calendar, schedule
        WHERE user_calendar.UID = schedule.UID AND user_calendar.UCNum = schedule.UCNum
        AND user_calendar.calNum = 1 AND user_calendar.UID = 1
        */

        $this->db->select("SdNum, startTime as start, lastTime as end , title, uc.UCNum");
        $this->db->from('user_calendar uc');
        $this->db->join('schedule s', 'uc.UID = s.UID AND uc.UCNum = s.UCNum');
        $this->db->where('uc.UID', $data->UID);
        if($data->calNum!=1)
            $this->db->where('uc.calNum', $data->calNum);
        /* calNum is Default Calendar */

        $result = $this->db->get()->result();

        return $result;
    }

    function getCalNum($data)
    {
        /* QUERY example
        SELECT calNum FROM user_calendar, schedule
        WHERE user_calendar.UID = schedule.UID AND user_calendar.UCNum = schedule.UCNum
        AND user_calendar.calNum = 1 AND user_calendar.UID = 1
        */
    }

    function insertUserCalendar($data)
    {
        $insertData = array
        (
            'UID'    => $data->UID,
            'calNum' => $data->calNum
        );
        $this->db->insert('user_calendar', $insertData);
        if($UCNum = $this->db->insert_id())
        {
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
            'UID'       => $data->UID,
            'UCNum'     => $data->UCNum,
            'startTime' => $data->start,
            'lastTime'  => $data->end,
            'title'     => $data->title,
            'content'   => $data->content,
        );

        $this->db->insert('schedule', $insertData);

        return $this->db->insert_id();
    }

    function updateCalendarANDSchedule($data)
    {
        /* QUERY example
        UPDATE schedule SET `startTime` = '2016-05-13 06:00:00', `lastTime` = '2016-05-16 08:00:00', `title` = 'QWEQRWQ', `content` = 'QWEWERWE'
        WHERE `schedule`.`SdNum` = 3
        */

        $updateData = array(

            'startTime' => $data->start,
            'lastTime'  => $data->end,
            'title'     => $data->title
        );

        $this->db->where('SdNum', $data->SdNum);
        $this->db->update('schedule', $updateData);
    }
}