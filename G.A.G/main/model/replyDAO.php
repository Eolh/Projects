<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-12-13
 * Time: 오후 10:30
 */
include_once "conf.php";

function insertreplyComment($data, $tableName)
{

    $data['r_content'] = mysql_escape_string($data['r_content']);

    connect_db();

    $sql = " INSERT INTO $tableName(p_num, m_num, r_content, r_date) ";
    $sql .= " VALUES('";
    $sql .= strval($data['p_num']) . "','" . strval($data['m_num']) . "','" . strval($data['r_content']) . "','" . strval($data['r_date']) . "')";


    $result = mysql_query($sql);

    mysql_close();
    return $result;
}

function updateReplyByNum($data, $tableName){
    connect_db();
    $data['content'] = mysql_escape_string($data['content']);
    $sql = " UPDATE $tableName SET ";
    $sql.= " r_content = '".strval($data['content'])."'";
    $sql.= " WHERE r_num = ".strval($data['rnum']);
var_dump($sql);
    $result = mysql_query($sql);
    mysql_close();
}

function selectReplyByNum($pnum, $tableName)
{

    connect_db();

    $sql = "SELECT * FROM $tableName WHERE p_num = '" . strval($pnum) . "' order by p_num";
    $result = mysql_query($sql);
    $cnt = 0;
    while ($row = mysql_fetch_array($result)) {

        $reply[$cnt]['m_num'] = $row['m_num'];
        $reply[$cnt]['p_num'] = $row['p_num'];
        $reply[$cnt]['r_num'] = $row['r_num'];
        $reply[$cnt]['r_content'] = $row['r_content'];
        $reply[$cnt]['r_date'] = $row['r_date'];
        $cnt++;
    }

    mysql_close();
    return $reply;
}

function deleteReplyByNum($renum,$tableName)        //멤버 삭제
{
    connect_db();
    $sql = "DELETE FROM $tableName WHERE r_num = $renum" ;
    var_dump($sql);
    $result = mysql_query($sql);
    mysql_close();
    return $result;
}

?>