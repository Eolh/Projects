<?php
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
?>
