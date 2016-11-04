<?php
class Gallery extends CI_Controller
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

    public function uploadImage()
    {
        $result = array();
        $resultData = "failed";

        $connection = mysqli_connect("localhost","root","duddnjs3wh","phptest"); //133.130.125.87
        mysqli_query($connection ,"set names utf8");

        $today = date("Y-m-d (H:i:s)");
        $fileName = $today.".jpg";//오늘날짜로 이름 변경

        if(move_uploaded_file($_FILES['file']['tmp_name'],"./public/imgData/".$fileName)){
            //change to 777
            chmod("./public/imgData/".$fileName, 0777);

            $query = mysqli_query($connection,"INSERT INTO GALLERY(imageName) VALUE('".$fileName. "')");

            if($query)
                $resultData = "success";
            else
                unlink("./public/imgData/".$fileName);
        }
        $result = array('result' => $resultData, 'imageName' => $fileName);
        mysqli_close($connection);

        echo json_encode($result);
    }

    public function load()
    {
        $result = array();
        $resultData = 'failed';

//데이터베이스에 연결합니다.
        $connection = mysqli_connect("localhost","root","duddnjs3wh","phptest");//133.130.125.87
        mysqli_query($connection ,"set names utf8");

//레코드를 불러옵니다
        $query = mysqli_query($connection, "SELECT * FROM GALLERY ORDER BY id DESC");

        if($query){
            $resultData = "success";
            $data = array();

            $num = mysqli_num_rows($query);

            for($i = 0 ; $i < $num ; $i++){
                mysqli_data_seek($query ,$i); //레코드 이동
                $row = mysqli_fetch_array($query);

                $id = $row['id'];
                $imageName = $row['imageName'];

                $data[$i] = array();
                $data[$i]['id'] = $id;
                $data[$i]['imageName'] = $imageName;
            }
        }
        $result = array('result' => $resultData, 'data' => $data);

        mysqli_close($connection);

        echo json_encode($result);
    }
}