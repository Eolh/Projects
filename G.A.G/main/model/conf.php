<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-11-04
 * Time: 오후 2:38
 */

function connect_db()   //db연결
{

    $dbInfo['hostname'] = "localhost";
    $dbInfo['username'] = "root";
    $dbInfo['password'] = "1234";

    $conn = mysql_connect($dbInfo['hostname'], $dbInfo['username'], $dbInfo['password']);
    mysql_query("SET NAMES utf8");
    mysql_select_db("mydb");

}

function getcategory()  //카테고리 가져옴
{

    $category=array('J','S','P','H','B','A');
    return $category;

}

function getPageInfo($pageNum, $cnt, $clpp, $cppb)      //페이지네이션 정보
{

    $CLPP = isset($clpp) ? $clpp : 10;
    $CPPB = isset($cppb) ? $cppb : 10;

    $countWholeRecord = $cnt;   //전체 레코드 갯수
    $countWholePage = ceil($countWholeRecord / $CLPP);  //전체 페이지 갯수
    $countWholeBlock = ceil($countWholePage / $CPPB); // 전체 블럭 갯수

    $currentBlockNum = ceil($pageNum / $CPPB); // 현재 페이지가 포함된 블럭 넘버
    $pageCountInlastBlock = $countWholePage - (($countWholeBlock - 1) * $CPPB); //마지막 블럭에 포함된 페이지 갯수

    $pageInfo['firstPage'] = ($pageNum == 1) ? false : true; //처음 페이지 표시 여부
    $pageInfo['lastPage'] = ($pageNum == $countWholePage) ? false : true; // 마지막 페이지 표시 여부
    $pageInfo['startPageNumInBlock'] = ($currentBlockNum - 1) * $CPPB + 1; // 현재 블럭에서 시작 페이지 번호
    $pageInfo['preBlock'] = ($pageNum <= $CPPB) ? 0 : $pageInfo['startPageNumInBlock'] - $CPPB; // 이전블럭 가기 표시 여부
    $pageInfo['nextBlock'] = ($currentBlockNum >= $countWholeBlock) ? 0 : $pageInfo['startPageNumInBlock'] + $CPPB; // 다음블럭 가기 표시 여부
    $pageInfo['currentPageNum'] = ($pageNum <= $countWholePage) ? $pageNum : $pageNum - 1; // 현재 페이지 번호
    $pageInfo['countPageInBlock'] = ($currentBlockNum != $countWholeBlock) ? $CPPB : $pageCountInlastBlock; // 현재 블럭에 표시할 페이지 갯수

    $pageInfo['CLPP'] = $CLPP;
    $pageInfo['CPPB'] = $CPPB;

    $pageInfo['countWholeRecord'] = $countWholeRecord;
    $pageInfo['countWholePage'] = $countWholePage;
    $pageInfo['countWholeBlock'] = $countWholeBlock;

    $pageInfo['currentBlockNum'] = $currentBlockNum;
    $pageInfo['pageCountInlastBlock'] = $pageCountInlastBlock;

    return $pageInfo;
}

function reArrayfiles($files_post)       //멀티파일 업로드 순서 정리
{

    $files = array();
    $files_key = array_keys($files_post);
    for ($i = 0; $i < count($files_post['name']); $i++) {
        foreach ($files_key as $key) {
            $files[$i][$key] = $files_post[$key][$i];
        }
    }

    return $files;

}

function singleFileUpload($uploadFileInfo, $uploadPath, $saveFileName, $fileMaxSize)    // 파일 업로드 처리 함수
{

    $targetDir = $uploadPath;
    $targetFile = $targetDir . basename($saveFileName);
    $imageFileType = pathinfo($targetFile, PATHINFO_EXTENSION);

    // 이미지 파일이 가짜 이미지 파일 인지 확인
    $check = getimagesize($uploadFileInfo["tmp_name"]);
    if ($check != false) {
        $returnArr['msg'][0] = "File is an image - " . $check["mime"] . ".";
        $returnArr['uploadOk'] = 1;
    } else {
        $returnArr['msg'][0] = "File is not an image.";
        $returnArr['uploadOk'] = 0;
    }

    // 대상 파일이 이미 존재하고 있는지 확인
    if (file_exists($targetFile)) {
        $returnArr['msg'][1] = "Sorry, file already exists.";
        $returnArr['uploadOk'] = 0;
    }

    // 파일의 SIZE가 정해진 크기 이내에 있는지 확인
    if ($uploadFileInfo["size"] > $fileMaxSize) {
        $returnArr['msg'][2] = "Sorry, your file is too large.";
        $returnArr['uploadOk'] = 0;
    }

    // 이미지 파일 포맷이 jpg, jpeg, png, gif 인지 확인
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        $returnArr['msg'][3] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $returnArr['uploadOk'] = 0;
    }

    // 위 모든 점검에 이상이 없는지 확인 후 파일 upload 실시
    if ($returnArr['uploadOk'] == 0) {
        $returnArr['msg'][4] = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($uploadFileInfo["tmp_name"], $targetFile)) {
            $returnArr['msg'][5] = "The file " . basename($uploadFileInfo["name"]) . " has been uploaded.";
        } else {
            $returnArr['msg'][5] = "Sorry, there was an error uploading your file.";
        }
    }

    return $returnArr;
}

function makeThumbnailImage($src, $dest, $desiredHeight, $imgFileType)  // 썸네일 이미지 생성 함수
{

    // 이미지 소스 파일을 읽어 온다.
    if ($imgFileType == "jpg" || $imgFileType == "jpeg") {
        $sourceImage = imagecreatefromjpeg($src);
    } elseif ($imgFileType == "png") {
        $sourceImage = imagecreatefrompng($src);
    } else {
        $sourceImage = imagecreatefromgif($src);
    }

    $width = imagesx($sourceImage);
    $height = imagesy($sourceImage);

    // 이미지 크기를 조정
    $desiredWidth = floor($width * ($desiredHeight / $height));

    // 버추얼 이미지를 생성
    $virtualImage = imagecreatetruecolor($desiredWidth, $desiredHeight);

    // 조정된 사이즈로 원본 이미지를 버추얼 이미지로 복사.
    imagecopyresampled($virtualImage, $sourceImage, 0, 0, 0, 0, $desiredWidth, $desiredHeight, $width, $height);

    // 지정된 위치에 thumbnail 이미지 생성
    if ($imgFileType == "jpg" || $imgFileType == "jpeg") {
        imagejpeg($virtualImage, $dest);
    } elseif ($imgFileType == "png") {
        imagepng($virtualImage, $dest);
    } else {
        imagegif($virtualImage, $dest);;
    }

}


function insertMultiFiles($data, $table)        //이미지파일 db저장
{
    connect_db();
    $sql = "insert into $table(p_num,savefile,filetype) ";
    $sql .= "values('{$data['p_num']}','{$data['savefile']}','{$data['filetype']}')";

    $result = mysql_query($sql);

    mysql_close();

    return $result;
}

function getFileCountwithPnum( $pnum, $table ){

    connect_db();
    $sql = " SELECT count(*) FROM $table WHERE p_num = ".strval($pnum);
    $result = mysql_query($sql);
    $count = mysql_result($result, 0, 0);

    mysql_close();
    return $count;
}

function singleFileDelete( $savePath, $saveFileName ){

    $targetDir = $savePath;
    $targetFile = $targetDir.basename($saveFileName);
    echo $targetFile;
    if( !file_exists($targetFile)){
        $returnArr['msg'] = $targetFile." 파일이 존재하지 않습니다.";
        $returnArr['result'] = 0;
    }
    else{
        unlink($targetFile);
        $returnArr['msg'] = $targetFile." 파일을 정상적으로 삭제하였습니다.";
        $returnArr['result'] = 1;
    }

    var_dump($returnArr);
    return $returnArr;
}


?>