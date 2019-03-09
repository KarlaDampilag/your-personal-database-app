<?php
	require_once 'config.php';

	//If the user is not logged in, redirect to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: login.php");
	    exit;
	}

	$obj_name = "";

	if (isset($_POST['object-name'])) {
		$obj_name = $_SESSION['object-name'] = $_POST['object-name'];
	} else {
		$obj_name = $_SESSION['object-name'];
	}

	if (isset($_POST['object-design-id'])) {
		$obj_design_id = $_SESSION['object-design-id'] = $_POST['object-design-id'];
	} else {
		$obj_design_id = $_SESSION['object-design-id'];
	}

	$obj_privacy = $_SESSION['object-privacy'] = $_POST['privacy'];

	$sql = "UPDATE object_designs SET privacy='$obj_privacy' WHERE id=$obj_design_id";

	$result = mysqli_query($conn, $sql);

	if ($result) {
		header("location: edit-object.php");
		exit;
	} else {
		echo "Database error: ".$conn->error;
	}

	mysqli_close($conn);
?>