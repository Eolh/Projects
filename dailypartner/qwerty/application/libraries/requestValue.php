<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class requestValue extends error_reporter
{
    /* insert POST GET VALUE */

    public $after;
    public $before;

    /*login*/
    public $userID;
    public $userPW;

    /* start: calendar && Schedule */
    public $title;
    public $content;
    public $start;
    public $end;
    public $SdNum;
    public $UCNum;
    public $file;
    public $startTime;
    public $endTime;
    public $groupCalendarRegister;
    public $location;
    public $groupCalRegisterOption;
    public $tag;
    public $userFile;
    public $number;
    public $UID;
    public $hashTag;
    public $FNum;
    public $filePath = "./public/file/";    /* PATH OF FILE */
    public $fileSize = 5242880;             /* 5MB */
    public $groupName;
    public $work_num;
    public $work;
    public $confirm_work;
    public $start_day;
    /* end    calendar && Schedule*/



    function insertProperty($property)
    {
        if (isset($property))
            foreach ($property as $key => $value)
            {
                if (property_exists($this, $key))
                    $this->$key = $value;
            }
    }

    function getAllProperty()
    {
        echo "<pre>";
        var_dump($this);
        echo "</pre>";
    }

    function getNumOfProperty()
    {
        $x = 0;
        foreach ($this as $key)
        {
            if ($key)
                $x++;
        }

        return $x;
    }
}