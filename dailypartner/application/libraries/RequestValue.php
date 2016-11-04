<?php

class requestValue extends error_reporter
{
    /* insert POST GET VALUE */
    public $username;
    public $id;
    public $pw;
    public $check;
    public $mailHeader;
    public $mailFooter;
    public $good;
    public $Uname;

    /* board */
    public $usernum;
    public $content;

    /* comment */
    public $boardnum;
    public $bnum;
    public $cnum;
    public $result;

    /* image Upload */
    public $name;
    public $type;
    public $tmp_name;
    public $error;
    public $size;
    const fileMaxSize = 15743640; /* 2mb */
    public $imagePath;

    /* comment reply */
    public $groupnum;
    public $depth;
    public $sort;

    /* userInfo Update */
    public $region;
    public $mail;
    public $phonenumber;
    public $birth;
    public $sex;
    public $tag;
    public $age;
    public $tempID;
    public $fileName;

    /* add Friend */
    public $request;
    public $accept;
    public $friend1;
    public $friend2;
    public $ID;
    public $friend_num;
    public $gname;
    public $gnum;
    public $uid;

    /* add notice */
    public $friendnum;

    public $work_num;
    public $work;
    public $confirm_check;
    public $start_day;
    public $UID;

    public $userID;
    public $userPW;

    /*maps*/
    public $startTime;
    public $lastTime;
    public $latitude;
    public $longitude;
    public $place_name;
    public $place_address;
    /* insert POST GET VALUE */

    public $after;
    public $before;

    /* start: calendar && Schedule */
    public $title;
    public $start;
    public $end;
    public $SdNum;
    public $UCNum;
    public $file;
    public $endTime;
    public $groupCalendarRegister;
    public $location;
    public $groupCalRegisterOption;
    public $userFile;
    public $number;
    public $hashTag;
    public $FNum;
    public $filePath = "./public/file/";    /* PATH OF FILE */
    public $fileSize = 15743640;             /* 5MB 5247880 15743640*/
    public $groupName;
    public $date;
    public $lastFNum;
    public $search;
    public $color;
    public $startTime2;
    public $endTime2;
    public $gm_id;
    public $firstRank;
    /* end    calendar && Schedule*/

    public $phoneNumber;
    public $keyWord;
    public $multiCalendar;

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