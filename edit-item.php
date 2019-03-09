<?php
	require_once 'config.php';

	//If the user is not logged in, redirect to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: login.php");
	    exit;
	}

	$obj_name = $_POST['object-name'];
	$item_id = $_POST['item-id'];

	$values = $keys = array();

	foreach($_POST as $key=>$value) {
		if ($key != 'object-name' && $key != 'item-id') {
			array_push($keys, $key);
			array_push($values, $value);
		}
	}

	$sql = "UPDATE $obj_name SET ";

	for ($i = 0; $i < count($keys); $i++) {
		$sql .= $keys[$i]."='$values[$i]'";

		if ($i != count($keys)-1){
			$sql .= ", ";
		}
	}

	$sql .= " WHERE id=$item_id";

	$result = mysqli_query($conn, $sql);

	if($result) {
		header("location: view-records.php");
		exit;
	} else {
		echo $conn->error;
	}

	mysqli_close($conn);
?>

