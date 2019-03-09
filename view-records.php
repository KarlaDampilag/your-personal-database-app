<?php
	require_once 'config.php';

	//If the user is not logged in, redirect to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: login.php");
	    exit;
	}

	$no_records_err = "";

	$obj_name = $display_obj_name = $obj_privacy = $obj_design_id = $add_entry = "";

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

	if (isset($_POST['add'])) {
		$add_entry = $_POST['add'];
	}

	$holder = explode('_', $obj_name);

	for ($i = 0; $i < count($holder); $i++){
		if ($i != count($holder)-1) {
			$display_obj_name .= $holder[$i] . " ";
		}
	}

	$display_obj_name = $_SESSION['display-name'] = ucwords($display_obj_name);
?>

<?php include 'header.php' ?>

<h2 id="object-name-view">Object Name: <?php echo $display_obj_name; ?></h3>
<h2 id="privacy-view">Privacy: <?php echo $obj_privacy ?></h3>

<div class="btn-group">
	<button class="btn no-shadow" onclick="showAddItemsForm();">Add item to object</button>
	<button class="btn no-shadow" onclick="confirmDeleteObject();">Delete object and its contents</button>
	<button class="btn no-shadow" onclick="sendObjectData();">Edit object design</button>
	<a href="index.php"><button class="btn no-shadow">Back to home page</button></a>
</div>

<div class="table-filters">
	<form id="table-filters-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
		<label>Search by: </label>
		<select id="search-by-option" name="search-by-option"><select>
		<input type="text" name="search-value" placeholder="Search value" required>
		<input type="submit" value="Search" class="btn no-shadow">
		<a href="view-records.php"><input type="button" class="btn no-shadow" value="Reload Table"></a>
	</form>
</div>

<div class="table-holder">
	<table id="object-home-table" class="records-table">
		<thead class="view-head">
			<tr id="view-head-row">
			<?php
				$sql = "SHOW COLUMNS FROM $obj_name";

				$result = mysqli_query($conn, $sql);

				$columns = array();

				if (mysqli_num_rows($result) > 0) {
					$count = 1;

					while($row = mysqli_fetch_assoc($result)) {
						$col_name = $row['Field'];

						if ($col_name != 'id' && $col_name != 'object_designs_id') {
							array_push($columns, $col_name);
							$display_name = ucfirst(str_replace('_', ' ', $col_name));
				?>
							<th><?php echo $display_name; ?><div class='icon-holder'><i class='icon-up-dir' onclick="sortTable('object-home-table', <?php echo $count; ?>, 'desc');"></i><i class='icon-down-dir' onclick="sortTable('object-home-table', <?php echo $count; ?>, 'asc');"></i></div></th>
			<?php
							$count++;
						}
					}
			?>
					<th>Edit</th><th>Delete</th>
			<?php
				} else {
					echo '0 columns';
				}
			?>
			</tr>
		</thead>
		<tbody class="view-body" id="<?php echo $obj_name ?>">
		<?php 
			$sql2 = "SELECT id, ";

			for($i = 0; $i < count($columns); $i++) {
				$sql2 .= " $columns[$i]";

				if ($i != count($columns)-1) {
					$sql2 .= ", ";
				}
			}

			$sql2 .= " FROM $obj_name";

			if (isset($_POST['search-value']) && $_POST['search-value'] != '') {
				$search_by = strtolower(str_replace(' ', '_', $_POST['search-by-option']));
				$search_option = $_POST['search-value'];

				$sql2 .= " WHERE $search_by='$search_option'";
			}

			$result2 = mysqli_query($conn, $sql2);

			if (mysqli_num_rows($result2) > 0) {
			    while($row = mysqli_fetch_assoc($result2)) {
			    	$index = 0;
			    	$id = $row['id'];
			    	$html = "<tr id='$id'>";

			    	for($i = 0; $i < count($columns); $i++) {
			    		$col = $columns[$index];
			    		$html .= "<td>$row[$col]</td>";
			    		$index++;
			    	}

			    	$html .= "<td><button class='btn edit-item-btn'>Edit</button></td><td><button class='btn delete-item-btn'>Delete</button></td></tr>";

			    	echo $html;
			    }
			} else {
				if (isset($_POST['search-value'])) {
			        $no_records_err = "Object name not found. <a href='view-records.php' class='error-msg'>Click to reload table.</a>";
			    } else {    		
					$no_records_err = "This object is currently empty. Start adding by clicking the 'Add items to object' button.";
				}
			}

			unset($_POST['search-value']);
			mysqli_close($conn);
		?>
		</tbody>
	</table>
</div>

<p><?php echo $no_records_err; ?></p>

<div class="btn-group">
	<button class="btn no-shadow" onclick="showAddItemsForm();">Add item to object</button>
	<button class="btn no-shadow" onclick="confirmDeleteObject();">Delete object and its contents</button>
	<button class="btn no-shadow" onclick="sendObjectData();">Edit object design</button>
	<a href="index.php"><button class="btn no-shadow">Back to home page</button></a>
</div>

<?php
	echo "<span style='visibility:hidden;' id='add-entry-value'>$add_entry</span>";
?>

<div id="add-items-form-parent" class="popup-form-div">
	<form id="add-items-form" class="popup-form" action="add-to-object.php" method="POST">
		<input type="hidden" name="object-name" value="<?php echo $obj_name ?>">
		<input type="hidden" name="object-design-id" value="<?php echo $obj_design_id ?>">
	</form>
</div>

<div id="edit-item-form-parent" class="popup-form-div">
	<form id="edit-item-form" class="popup-form" action="edit-item.php" method="POST">
		<input type="hidden" name="object-name" value="<?php echo $obj_name ?>">
	</form>
</div>

<div id="edit-object-form-parent" class="popup-form-div">
	<form id="edit-object-form" class="popup-form" action="edit-object.php" method="POST">
		<input type="hidden" name="object-name" value="<?php echo $obj_name ?>">
		<input type="hidden" name="object-design-id" value="<?php echo $obj_design_id ?>">
		<input type="hidden" name="object-privacy" value="<?php echo $obj_privacy ?>">
	</form>
</div>

<div id="delete-object-form-parent" class="popup-form-div">
	<form id="delete-object-form-records" class="popup-form" action="delete-object.php" method="POST">
		<p>Are you sure you want to <span style="color:red;">PERMANENTLY</span> delete this object design and all of its contents from the database?</p> 
		<p><strong>WARNING: This action will be irreversible and you can not retrieve nor restore this data in the future.</strong></p>
		<input type="hidden" name="object-name" value="<?php echo $obj_name ?>">
		<input type="hidden" name="object-design-id" value="<?php echo $obj_design_id ?>">
	</form>
</div>

<?php include 'footer.php' ?>