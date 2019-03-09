<?php
	require_once 'config.php';

	//If the user is not logged in, redirect to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: login.php");
	    exit;
	}

	$obj_name = $col_delete = "";

	if (isset($_POST['object-name'])) {
		$obj_name = $_SESSION['object-name'] = $_POST['object-name'];
	} else {
		$obj_name = $_SESSION['object-name'];
	}

	if (isset($_POST['col-to-delete'])) {
		$col_delete = $_SESSION['col-to-delete'] = $_POST['col-to-delete'];
	} else {
		$col_delete = $_SESSION['col-to-delete'];
	}

	$delete = "ALTER TABLE $obj_name DROP COLUMN $col_delete";

	$result_delete = mysqli_query($conn, $delete);

	if ($result_delete) {
		header("location: edit-object.php");
		exit;
	} else {
		echo "Database error: ".$conn->error;
	}

	mysqli_close($conn);
?>