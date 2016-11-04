<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * MODEL - CTL 연결을 제외한 CHECK 사항은 이곳에서 단락별로 함수화한다.
 * 코드의 가독성을 높이기 위함
 *
 * */
class presenter extends error_reporter
{
    function insertCalendarScheduleOptionCheck()
    {
        if($_POST['UCNum'] != $postTemper = (int)$_POST['groupCalRegisterOption'])
        {
            $_POST['UCNum'] = $postTemper;
            unset($_POST['groupCalRegisterOption']);
        } /* 선택된 옵션태그에 맞는 UCNum 변환 */

        if($_POST['startTime'])
        {
            $postTemper = explode(" " ,$_POST['start']);
            $_POST['start'] = $postTemper[0] . " " . $_POST['startTime'];
            unset($_POST['startTime']);
        } /* 만약 시간 설정이 있었을 경우 start 변환 */

        if($_POST['endTime'])
        {
            $postTemper = explode(" " ,$_POST['end']);
            $_POST['end'] = $postTemper[0] . " " . $_POST['endTime'];
            unset($_POST['endTime']);
        } /* 만약 시간 설정이 있었을 경우 end 변환 */
    }

    function fileGroupUpload($preData)
    {
        /* start: config */
        $position   = $preData->number;
        $sizeCheck  = $preData->userFile['size'][$position];
        $maxSize    = $preData->fileSize;
        $uploadPath = $preData->filePath;
        /* end:   config */

        if($sizeCheck > $maxSize) {
            $check = false;
            $preData->check = $check;
            return $preData;
        } else {
            $check = true;
        } /* fileSize Check */

        $preData->userFile['Flink'][$position] = $preData->userFile['name'][$position];

        do
        {
            $preData->lastFNum = $preData->lastFNum+1;

            $tempFName = (String)pathinfo($preData->userFile['name'][$position], PATHINFO_FILENAME);
            $tempType =  (String)pathinfo($preData->userFile['name'][$position], PATHINFO_EXTENSION);
            $preData->userFile['name'][$position]  = $preData->lastFNum."_"."group_".$preData->gnum.".".$tempType;

        }while(file_exists($uploadPath.$preData->userFile['name'][$position]));

        $uploadPath = $uploadPath.basename($preData->userFile['name'][$position]);

        $uploadPath = iconv("ISO-8859-1", "UTF-8", $uploadPath);
        /* UTF-8 */

        if($check) {
            move_uploaded_file($preData->userFile['tmp_name'][$position], $uploadPath);
        }
        /* success */

        $preData->check = $check;

        return $preData;
    }

    function fileUpload($preData)
    {
        /* start: config */
        $position   = $preData->number;
        $sizeCheck  = $preData->userFile['size'][$position];
        $maxSize    = $preData->fileSize;
        $uploadPath = $preData->filePath;
        /* end:   config */

        if($sizeCheck > $maxSize) {
            $check = false;
            $preData->check = $check;
            return $preData;
        } else {
            $check = true;
        } /* fileSize Check */

        $preData->userFile['Flink'][$position] = $preData->userFile['name'][$position];

        do
        {
            $preData->lastFNum = $preData->lastFNum+1;

            $tempFName = (String)pathinfo($preData->userFile['name'][$position], PATHINFO_FILENAME);
            $tempType = (String)pathinfo($preData->userFile['name'][$position], PATHINFO_EXTENSION);
            $preData->userFile['name'][$position]  = $preData->lastFNum."_".$preData->ID.".".$tempType;
            //$preData->userFile['name'][$position]  = $tempFName."(1)".".".$tempType;
        }while(file_exists($uploadPath.$preData->userFile['name'][$position]));

        $uploadPath = $uploadPath.basename($preData->userFile['name'][$position]);

        $uploadPath = iconv("ISO-8859-1", "UTF-8", $uploadPath);
        /* UTF-8 */

        if($check) {
            move_uploaded_file($preData->userFile['tmp_name'][$position], $uploadPath);
        }
        /* success */

        $preData->check = $check;

        return $preData;
    }
}