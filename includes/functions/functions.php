<?php

// print the title page

function getTitle(){
	global $pagetitle;

	if (isset($pagetitle)) {
		echo $pagetitle;
	}else{
		echo "Default";
	}
}

// get all records from any table

function getAll($faild,$table,$where = NULL,$and = NULL,$orderfaild,$ordaring = "DESC"){

	global $conn;

	$stmt= $conn->prepare("SELECT $faild FROM $table $where $and ORDER BY $orderfaild $ordaring");
	$stmt->execute();
	$getAll = $stmt->fetchAll();
	return $getAll;
}

// check if user is not activated

function checkUser($user){

	global $conn;
	
	$stmtu = $conn->prepare("SELECT Username,RegStatus FROM users WHERE Username = ? AND RegStatus = 0");
	$stmtu->execute(array($user));
	$status = $stmtu->rowCount();

	return $status;
}

// function to check items in database

function checkItem($select,$from,$value){

	global $conn;

	$statment = $conn->prepare("SELECT $select FROM $from WHERE $select = :value");
	$statment->bindParam(":value",$value);
	$statment->execute();
	$count = $statment->rowCount();

	return $count;
}














////////////////// Back End Function /////////////////////////



// whene there's the message back to home page
function redirectHome($theMsg, $url = null, $seconds = 4){

	if ($url === null) {

		$url = 'index.php';
	}else{

		$url = isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';	
	}

	echo $theMsg;

	echo "<div class='alert alert-info'>You will be redirected to back after $seconds seconds</div>";

	header("refresh:$seconds;url=$url");

	exit();
}



// count Items 

function countItems($item, $table){

	global $conn;

	$stmt = $conn->prepare("SELECT count($item) FROM $table");
	$stmt->execute();

	return $stmt->fetchColumn();
}

// to get latest from database
// order like [UserId , Username]

function getLatest($select,$table,$order,$limit = 5){

	global $conn;

	$stmt= $conn->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
	$stmt->execute();
	$rows = $stmt->fetchAll();
	return $rows;
}







?>