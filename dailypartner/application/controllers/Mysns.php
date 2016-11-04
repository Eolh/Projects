<?php
class Mysns extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        /* start of database */
        $this->load->database();

        /* end of database */

        ///////////////////////////////////////////////////////////////////

        /* start of library */
        $this->load->library("error_reporter");
        $this->load->library("requestValue");
        /* end of library */
    }

    public function index()
    {
        $this->load->view("/welcome_message");
    }

    public function commentDel()
    {
        $result = array();
        $callback = $_GET['callback'];
        $postId = $_GET['postId'];
        $resultData = 'failed';
        //데이터베이스에 연결합니다.
        $connection = mysqli_connect("localhost","root","duddnjs3wh","phptest");
        mysqli_query("set names utf8");//한글 안깨지도록 유니코드
        //새로운 레코드를 삽입
        $query = mysqli_query($connection,"DELETE FROM MYSNS_COMMENT WHERE pid = ".$postId);
        //쿼리문이 성공했나
        if($query) $resultData = "success";
        $result = array('result' => $resultData);
        mysqli_close($connection);
        echo $callback."(".json_encode($result).")";
    }
    public function commentLoad()
    {
        $result = array();
        $callback = $_GET['callback'];
        $postId = $_GET['postId'];
        $resultData = 'failed';
        //데이터베이스에 연결합니다.
        $connection = mysqli_connect("localhost","root","duddnjs3wh","phptest");
        mysqli_query("set names utf8");//한글 안깨지도록 유니코드
        //새로운 레코드를 삽입
        $query = mysqli_query($connection,"SELECT * FROM MYSNS_COMMENT WHERE pparent = ".$postId." ORDER BY pid DESC");
        //쿼리문이 성공했나
        if($query){
            $resultData = "success";
            $data = array();
            $num = mysqli_num_rows($query);
            for($i = 0; $i< $num; $i++){
                mysqli_data_seek($i);
                $row = mysqli_fetch_array($query);
                $id = $row['pid'];
                $content = $row['pcontent'];
                $writer = $row['pwriter'];
                $writedate = $row['pwritedate'];
                $data[$i] = array();
                $data[$i]['id'] = $id;
                $data[$i]['content'] = $content;
                $data[$i]['writer'] = $writer;
                $data[$i]['writedate'] = $writedate;
            }
        }
        $result = array('result' => $resultData, 'data' => $data);
        mysqli_close($connection);
        echo $callback."(".json_encode($result).")";
    }
    public function commentPost()
    {
        $result = array();
        $callback = $_GET['callback'];
        $parentId = $_GET['parentId'];
        $content = $_GET['content'];
        $writer = $_GET['writer'];
        $writedate = date('Y-m-d (H:i:s)');
        $resultData = 'failed';
        //데이터베이스에 연결합니다.
        $connection = mysqli_connect("localhost","root","duddnjs3wh","phptest");
        mysqli_query("set names utf8");//한글 안깨지도록 유니코드
        //새로운 레코드를 삽입
        $query = mysqli_query($connection,"INSERT INTO MYSNS_COMMENT(pparent, pcontent, pwritedate, pwriter) VALUES('".$parentId."', '".$content."', '".$writedate."','".$writer."')");
        //쿼리문이 성공했나
        if($query)$resultData = "success";
        $lid = mysql_insert_id($connection);
        $result = array('result' => $resultData, 'lastId' => $lid);
        mysqli_close($connection);
        echo $callback."(".json_encode($result).")";
    }
    public function del()
    {
        $result = array();
        $callback = $_GET['callback'];
        $postId = $_GET['postId'];
        $resultData = 'failed';
        //데이터베이스에 연결합니다.
        $connection = mysqli_connect("localhost","root","duddnjs3wh","phptest");
        mysqli_query("set names utf8");//한글 안깨지도록 유니코드
        //댓글 삭제
        $query = mysqli_query($connection,"DELETE FROM MYSNS_COMMENT WHERE pparent = ". $postId);
        //글 삭제
        $query = mysqli_query($connection,"DELETE FROM MYSNS_POST WHERE pid = ". $postId);

        //쿼리문이 성공했나
        if($query)$resultData = "success";
        $result = array('result' => $resultData);
        mysqli_close($connection);
        echo $callback."(".json_encode($result).")";
    }
    public function join()
    {
        $result = array();
        $callback = $_GET['callback'];
        $id = $_GET['id'];
        $pw = $_GET['pw'];
        $resultData = 'failed';
        //데이터베이스에 연결합니다.
        $connection = mysqli_connect("localhost","root","duddnjs3wh","phptest");
        mysqli_query("set names utf8");//한글 안깨지도록 유니코드
        //새로운 레코드 삽입
        $query = mysqli_query($connection,"INSERT INTO MYSNS_ACCOUNT(mid, mpw) VALUES('".$id."','".$pw."')");
        //쿼리문이 성공했다면 회원가입에 성공
        if($query) $resultData = "success";
        $result = array('result' => $resultData);
        mysqli_close($connection);
        echo $callback."(".json_encode($result).")";
    }
    public function load()
    {
        $result = array();
        $callback = $_GET['callback'];
        $resultData = 'failed';
        //데이터베이스에 연결합니다.
        $connection = mysqli_connect("localhost","root","duddnjs3wh","phptest");
        mysqli_query("set names utf8");//한글 안깨지도록 유니코드
        //새로운 레코드를 삽입
        $query = mysqli_query($connection,"SELECT * FROM MYSNS_POST ORDER BY pid DESC");
        //쿼리문이 성공했나
        if($query){
            $resultData = "success";
            $data = array();
            $num = mysqli_num_rows($query);
            for($i = 0; $i< $num; $i++){
                mysqli_data_seek($i);
                $row = mysqli_fetch_array($query);
                $id = $row['pid'];
                $subject = $row['psubject'];
                $content = $row['pcontent'];
                $writer = $row['pwriter'];
                $writedate = $row['pwritedate'];
                $data[$i] = array();
                $data[$i]['id'] = $id;
                $data[$i]['subject'] = $subject;
                $data[$i]['content'] = $content;
                $data[$i]['writer'] = $writer;
                $data[$i]['writedate'] = $writedate;
            }
        }
        $result = array('result' => $resultData, 'data' =>$data);
        mysqli_close($connection);
        echo $callback."(".json_encode($result).")";
    }
    public function login()
    {
        $result = array();
        $callback = $_GET['callback'];
        $id = $_GET['id'];
        $pw = $_GET['pw'];
        $resultData = 'failed';
        //데이터베이스에 연결합니다.
        $connection = mysqli_connect("localhost","root","duddnjs3wh","phptest");
        mysqli_query("set names utf8");//한글 안깨지도록 유니코드
        //ID와 비밀번호가 일치하는 데이터만 선택
        $query = mysqli_query($connection,"SELECT * FROM MYSNS_ACCOUNT WHERE mid = '".$id."' AND mpw = '".$pw."'");
        //쿼리문이 이상없는가
        if($query){
            //쿼리문의 수행결과로 반환된 레코드 갯수 가져옴
            $dataCnt = mysqli_num_rows($query);
            //$dataCnt 가 0이 아니면 데이터가 있다는 거
            //올바른 아이디와 비밀번호를 입력한 것
            if($dataCnt)
                $resultData = "success";
            else
                $resultData = "wrong";
        }
        $result = array('result' => $resultData);
        mysqli_close($connection);
        echo $callback."(".json_encode($result).")";
    }
    public function post()
    {
        $result = array();
        $callback = $_GET['callback'];
        $subject = $_GET['subject'];
        $content = $_GET['content'];
        $writer = $_GET['writer'];
        $writedate = date('Y-m-d (H:i:s)');
        $resultData = 'failed';
        //데이터베이스에 연결합니다.
        $connection = mysqli_connect("localhost","root","duddnjs3wh","phptest");
        mysqli_query("set names utf8");//한글 안깨지도록 유니코드
        //새로운 레코드를 삽입
        $query = mysqli_query($connection,"INSERT INTO MYSNS_POST(psubject, pcontent, pwritedate, pwriter) VALUES('".$subject."', '".$content."', '".$writedate."','".$writer."')");
        //쿼리문이 성공했나
        if($query)$resultData = "success";
        $result = array('result' => $resultData);
        mysqli_close($connection);
        echo $callback."(".json_encode($result).")";
    }
}