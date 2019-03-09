<?php
	require_once 'config.php';

	//If the user is not logged in, redirect to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: login.php");
	    exit;
	}

	$obj_name = $_POST['object-name'];
	$obj_design_id = $_POST['object-design-id'];

	$sql1 = "DROP TABLE $obj_name";

	$result1 = mysqli_query($conn, $sql1);

	$sql2 = "DELETE FROM object_designs WHERE id = $obj_design_id";

	$result2 = mysqli_query($conn, $sql2);

	if($result1 && $result2) {
        header('Location: index.php');
        exit;
	} else {
		echo $conn->error;
	}

	mysqli_close($conn);
?>