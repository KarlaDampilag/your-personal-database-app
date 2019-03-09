<?php
	require_once 'config.php';

	//If the user is not logged in, redirect to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: login.php");
	    exit;
	}

	$obj_name = $_POST['object-name'];
	$obj_design_id = $_POST['object-design-id'];

	$sql = "INSERT INTO $obj_name (object_designs_id";

	foreach($_POST as $name => $value) {
		if($name !== 'object-name' && $name !== 'object-design-id') {
			$sql .= ", $name";
		}
	}

	$sql .= ") VALUES ($obj_design_id, ";

	foreach($_POST as $name => $value) {
		if($name !== 'object-name' && $name !== 'object-design-id') {
			$sql .= "'$value', ";
		}
	}

	$sql = substr($sql, 0, -2).")";

	echo $sql.'<br>';

	//$result = mysqli_query($conn, $sql);

	if ($conn->query($sql) === TRUE) {
		header("location: view-records.php");
		exit;
	} else {
		echo $conn->error;
	}

	mysqli_close($conn);
?>

