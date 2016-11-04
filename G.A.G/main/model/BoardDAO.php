<?php
/**
 * Created by PhpStorm.
 * User: JH
 * Date: 2015-12-13
 * Time: 오후 6:32
 */

function getBoardCountWithCategory($boardTable, $search)
{
    connect_db();
    $sql = "select count(*) from $boardTable ";
    if ($search != null)
        $sql .= "where b_subject like %$search%";

    $result = mysql_query($sql);
    $cnt = mysql_result($result, 0, 0);
    mysql_close();
    return $cnt;
}

function selectBoardbyNum($bnum, $table)      // 글에 대한 값을 받을때
{
    connect_db();
    $sql = "SELECT * FROM {$table} WHERE b_num = " . strval($bnum);
    $result = mysql_query($sql);

    if ($result) {
        $row = mysql_fetch_array($result);
        $board['b_num'] = $row['b_num'];
        $board['m_num'] = $row['m_num'];
        $board['b_subject'] = $row['b_subject'];
        $board['b_content'] = $row['b_content'];
        $board['b_hit'] = $row['b_hit'];
        $board['b_date'] = $row['b_date'];

    }

    mysql_close();
    return $board;
}

function getparentdata($point, $table)
{
    connect_db();
    $sql = "select * from $table where b_num = '$point'";
    var_dump($sql);
    $result = mysql_query($sql);
    if ($result) {
        $row = mysql_fetch_array($result);
        var_dump($row);
        $board['b_groupnum'] = $row['b_groupnum'];
        $board['b_order'] = $row['b_order'];
        $board['b_depth'] = $row['b_depth'];
    }
    mysql_close();
    return $board;
}

function selectBoardListWithPageInfoAndCategory($PageInfo, $boardTable, $search)  //게시판별 글리스트 출력
{

    connect_db();
    $CLPP = isset($PageInfo['CLPP']) ? $PageInfo['CLPP'] : 10;
    $limitStart = ($PageInfo['currentPageNum'] - 1) * $CLPP;

    $sql = "SELECT * FROM $boardTable ";
    if ($search != null)
        $sql .= "where b_subject like %$search%";
    $sql .= " order by b_groupnum desc,b_order asc limit $limitStart,$CLPP";
    $result = mysql_query($sql);
    $cnt = 0;
    while ($row = mysql_fetch_array($result)) {

        $board[$cnt]['b_num'] = $row['b_num'];
        $board[$cnt]['m_num'] = $row['m_num'];
        $board[$cnt]['b_subject'] = $row['b_subject'];
        $board[$cnt]['b_hit'] = $row['b_hit'];
        $board[$cnt]['b_date'] = $row['b_date'];
        $board[$cnt]['b_depth'] = $row['b_depth'];

        $sql = "select m_id from member where m_num ='{$row['m_num']}'";
        $result2 = mysql_query($sql);
        if ($result2) {
            $row2 = mysql_fetch_array($result2);
            $board[$cnt]['m_id'] = $row2['m_id'];
        }
        $cnt++;
    }


    mysql_close();
    return $board;
}

function deleteBoard($bnum, $table)  //제품 삭제
{
    connect_db();
    $sql = "delete from $table where b_num = $bnum";
    mysql_query($sql);

    mysql_close();

}

function insertBoard($data)    //글 작성
{
    connect_db();
    $sql = "insert into {$data['b_table']}(m_num,b_subject,b_content,b_date) ";
    $sql .= "values('{$data['m_num']}','{$data['b_subject']}','{$data['b_content']}','{$data['b_date']}')";
    mysql_query($sql);

    $getBnum = mysql_insert_id();
    $sql2 = " UPDATE {$data['b_table']} SET b_groupnum = " . strval($getBnum) . " WHERE b_num = " . strval($getBnum);
    $result = mysql_query($sql2);
    return $getBnum;

    mysql_close();
}

function insertreBoard($data, $p_data)
{
    connect_db();
    $sql = "SELECT ifnull(MIN(b_order),0) FROM {$data['b_table']} WHERE b_groupnum = {$p_data['b_groupnum']} AND b_order > {$p_data['b_order']} AND b_depth <= {$p_data['b_depth']}";
    $result = mysql_query($sql);
    $cnt = mysql_result($result, 0, 0);

    if($cnt==0){
        $sql2="SELECT ifnull(MAX(b_order),0) + 1 FROM {$data['b_table']} WHERE b_groupnum = {$p_data['b_groupnum']}";
        $result = mysql_query($sql2);
        $order = mysql_result($result, 0, 0);
        $sql3 = "insert into {$data['b_table']}(m_num,b_subject,b_content,b_date,b_groupnum,b_order,b_depth) ";
        $sql3 .= "values('{$data['m_num']}','{$data['b_subject']}','{$data['b_content']}','{$data['b_date']}','{$p_data['b_groupnum']}','{$order}','".strval($p_data['b_depth']+1)."')";
    }
    else{
        $sql2="UPDATE {$data['b_table']} SET b_order = b_order + 1 WHERE b_groupnum = {$p_data['b_groupnum']} AND b_order >= $cnt";
        mysql_query($sql2);
        $sql3 = "insert into {$data['b_table']}(m_num,b_subject,b_content,b_date,b_groupnum,b_order,b_depth) ";
        $sql3 .= "values('{$data['m_num']}','{$data['b_subject']}','{$data['b_content']}','{$data['b_date']}','{$p_data['b_groupnum']}','{$cnt}','".strval($p_data['b_depth']+1)."')";
    }
    mysql_query($sql3);

    $getBnum = mysql_insert_id();

    return $getBnum;
    mysql_close();
}

function increaseHit($b_num, $table)
{

    connect_db();

    $sql1 = " SELECT b_hit FROM $table WHERE b_num = " . strval($b_num);
    $result = mysql_query($sql1);
    $newHitCount = mysql_result($result, 0, 0) + 1;
    var_dump($sql1);
    $sql2 = " UPDATE $table SET b_hit = " . strval($newHitCount) . " WHERE b_num = " . strval($b_num);
    $result = mysql_query($sql2);

    mysql_close();

}

?>