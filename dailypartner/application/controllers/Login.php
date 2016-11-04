<?php

class Login extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        /* start of database */
        $this->load->database();
        $this->load->model('Login_m');

        /* end of database */

        ///////////////////////////////////////////////////////////////////

        /* start of library */
        $this->load->library("error_reporter");
        $this->load->library("requestValue");
        /* end of library */
    }

    public function index()
    {
        if (!$_SESSION)
            $this->load->view("/login/title"); else
            header("location:/main/");
    }

    public function logout()
    {
        session_destroy();
        $this->load->view("/login/title");
    }

    public function user_check()
    {
        if ($_POST) {

            $ajaxData = new requestValue();
            $ajaxData->insertProperty($_POST);
            $rec = $this->Login_m->checkAccount($ajaxData);

            if ($rec) {
                $_SESSION['login']['ID'] = $rec[0]->ID;
                $_SESSION['login']['UID'] = $rec[0]->UID;

                echo json_encode(true);
            } else {//x
                $modalName = '#login-join'; //값이 아예없을경우

                echo json_encode($modalName);
            }
        } else {//x
            $modalName = '#login-error';

            echo json_encode($modalName);
        }
    }

    public function load()
    {
        $result = array();
        $callback = $_GET['callback'];
        $resultData = 'failed';

        $connection = mysqli_connect("localhost","root","duddnjs3wh", "phptest");
        mysqli_query("set names utf8");

        $query = mysqli_query($connection, "SELECT * FROM MYGALLERY ORDER BY id DESC");

        if($query){
            $resultData = "success";
            $data = array();

            $num = mysqli_num_rows($query);

            for($i = 0 ; $i < $num ; $i++){
                mysqli_data_seek($i);
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

        echo $callback."(".json_encode($result).")";
    }

    public function uploadimage()
    {
        $result = array();
        $resultData = "failed";

        $connection = mysqli_connect("localhost", "root", "duddnjs3wh", "phptest");
        mysqli_query("set name utf8");

        $today = date("Y-m-d (H:i:s)");
        $fileName = $today . ".jpg";

        if (move_uploaded_file($_FILES['file']['tmp_name'], "./public/imgData/" . $fileName)) {
            chmod("./public/imgData/" . $fileName, 0777);

            $query = mysqli_query($connection, "INSERT INTO MYGALLERY(imageName) VALUE('" . $fileName . "')");

            if ($query)
                $resultData = "success";
            else
                unlink("./public/imgData/" . $fileName);
        }
        $result = array('result' => $resultData, 'imageName' => $fileName);

        mysqli_close($connection);

        echo json_encode($result);
    }
}