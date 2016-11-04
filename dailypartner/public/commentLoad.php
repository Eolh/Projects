<?php
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
?>
