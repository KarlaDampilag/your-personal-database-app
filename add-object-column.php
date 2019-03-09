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

	$new_cols = array();

	foreach ($_POST as $name => $value) {

		if (substr($name, 0, 8) == 'new-col-') {
			array_push($new_cols, $value);
			echo $value.'<br>';

		}
	}

	$add = "ALTER TABLE $obj_name ";

	for($i = 0; $i < count($new_cols); $i++) {
        $add .= 'ADD COLUMN ' . str_replace(' ', '_', strtolower($new_cols[$i])) . ' VARCHAR(250) NOT NULL';

        if ($i != count($new_cols)-1) {
			$add .= ", ";
		}
    }

	$result_add = mysqli_query($conn, $add);

	if ($result_add) {
		header("location: edit-object.php");
		exit;
	} else {
		echo "Database error: ".$conn->error;
	}

	mysqli_close($conn);
?>