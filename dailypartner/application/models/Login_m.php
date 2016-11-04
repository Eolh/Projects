<?php

class Login_m extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }

    function checkAccount($userInfo)
    {
        $this->db->select("*"); //조회할 속성명
        $this->db->from('user'); //테이블 명
        $this->db->where('ID',$userInfo->userID);
        $this->db->where('Password',$userInfo->userPW);

        $result = $this->db->get()->result();

        return $result; //boolean 값?
    }
}

?>