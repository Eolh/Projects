<?php
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
?>
