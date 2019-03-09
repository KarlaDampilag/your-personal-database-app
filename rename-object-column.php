<?php
	require_once 'config.php';

	//If the user is not logged in, redirect to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: login.php");
	    exit;
	}

	$obj_name = $obj_privacy = $obj_design_id = "";

	if (isset($_POST['object-name'])) {
		$obj_name = $_SESSION['object-name'] = $_POST['object-name'];
	} else {
		$obj_name = $_SESSION['object-name'];
	}

	if (isset($_POST['object-privacy'])) {
		$obj_privacy = $_SESSION['object-privacy'] = $_POST['object-privacy'];
	} else {
		$obj_privacy = $_SESSION['object-privacy'];
	}

	if (isset($_POST['object-design-id'])) {
		$obj_design_id = $_SESSION['object-design-id'] = $_POST['object-design-id'];
	} else {
		$obj_design_id = $_SESSION['object-design-id'];
	}

	$keys = $values = array();

	foreach($_POST as $key=>$value) {
		if ($key != 'object-name' && $key != 'object-design-id' && $key != 'privacy' && $key != 'new-cols' && $key != 'edit-object-action') {
			array_push($keys, $key);
			array_push($values, strtolower(str_replace(' ', '_', $value)));
		}
	}

	$rename = "ALTER TABLE $obj_name ";

	for ($i = 0; $i < count($keys); $i++) {
		$rename .= " CHANGE ".$keys[$i]." $values[$i] varchar(250)";

		if ($i != count($keys)-1){
			$rename .= ", ";
		}
	}

	$result_rename = mysqli_query($conn, $rename);

	if ($result_rename) {
		echo "<script>alert('Changes have been saved.'); window.location = 'edit-object.php';</script>";
	} else {
		echo "Database error: ".$conn->error;
	}

	mysqli_close($conn);
?>