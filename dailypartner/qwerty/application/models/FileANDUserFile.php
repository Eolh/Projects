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
}