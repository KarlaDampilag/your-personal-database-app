<?php
	require_once 'config.php';

	//If the user is not logged in, redirect to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: login.php");
	    exit;
	}

	$obj_name = $_POST['object-name'];
	$obj_id = $_POST['object-id'];

	$sql = "DELETE FROM $obj_name WHERE id = $obj_id";

	//echo $sql;

	$result = mysqli_query($conn, $sql);

	if($result) {
		header("location: view-records.php");
		exit;
	} else {
		echo $conn->error;
	}

	mysqli_close($conn);
?>

