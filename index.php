<?php
	require_once 'config.php';

	//If the user is not logged in, redirect to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
	    header("location: login.php");
	    exit;
	}

	$no_records_err = $add_btn = "";
	$object_design_id = $object_name = "";
?>

<?php include 'header.php' ?>

		<div class="table-filters">
			<form id="table-filters-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
				<input type="text" name="search-input" placeholder="Object name" required>
				<input type="submit" class="btn no-shadow" value="Search">
				<a href="index.php"><input type="button" class="btn no-shadow" value="Reload Table"></a>
				<div class="give-space">
					<label>Show:</label>
					<input type="radio" name="privacy-filter" value="all" onchange="filterByPrivacy('index-table');"
					<?php
						if ((!isset($_POST['privacy-filter'])) || (isset($_POST['privacy-filter']) && $_POST['privacy-filter'] === 'all')) {
							echo "checked";
						}
					?>
					> All
					<input type="radio" name="privacy-filter" value="public" onchange="filterByPrivacy('index-table');"
					<?php
						if (isset($_POST['privacy-filter']) && $_POST['privacy-filter'] === 'public') {
							echo "checked";
						}
					?>
					> Public
					<input type="radio" name="privacy-filter" value="private" onchange="filterByPrivacy('index-table');"
					<?php
						if (isset($_POST['privacy-filter']) && $_POST['privacy-filter'] === 'private') {
							echo "checked";
						}
					?>
					> Private
				</div>
			</form>
		</div>

		<div class="table-holder">
			<table id="index-table" class="records-table">
				<thead>
					<tr>
						<th class="no-border">Object Name<div class="icon-holder"><i class="icon-up-dir" onclick="sortTable('index-table', 1, 'desc');"></i><i class="icon-down-dir" onclick="sortTable('index-table', 1, 'asc');"></i></div></th>
						<th>No. of Entries<div class="icon-holder"><i class="icon-up-dir" onclick="sortTable('index-table', 2, 'asc');"></i><i class="icon-down-dir" onclick="sortTable('index-table', 2, 'desc');"></i></div></th>
						<th>Privacy</th>
						<th>Add Entry</th>
						<th>View Object</th>
						<th>Edit Object Design</th>
						<th>Delete Object</th>
					</tr>
				</thead>
				<tbody>

				<?php
		        	$sql = "SELECT * FROM object_designs WHERE user_id = " . $_SESSION['id'];

		        	if (isset($_POST['search-input']) && $_POST['search-input'] != '') {
			            $search_input = $_POST['search-input'];
			            $sql .= " AND name='$search_input'";
			        }

			        if (isset($_POST['privacy-filter'])) {
			        	if ($_POST['privacy-filter'] === 'public') {
				        	$sql .= " AND privacy='public'";
				        } else if ($_POST['privacy-filter'] === 'private') {
				        	$sql .= " AND privacy='private'";
				        }
			        }
			        
		        	$result = mysqli_query($conn, $sql);

		        	if (mysqli_num_rows($result) > 0) {
			            while($row = mysqli_fetch_assoc($result)) {
			            	$object_id = $object_design_id = $row['id'];
			            	$user_id = $_SESSION['id'];
			            	$object_name = strtolower($row['name']).'_'.$user_id;
			            	$display_name = ucwords(str_replace('_', ' ', $row["name"]));
			            	$privacy = $row["privacy"];

			            	$sql2 = "SELECT * FROM $object_name WHERE object_designs_id = $object_id";

			            	$result2 = mysqli_query($conn, $sql2);

			            	$object_rows = mysqli_num_rows($result2);

			            	echo "<tr id='$object_name'><input type='hidden' name='object-design-id' value='$object_id'><input type='hidden' name='privacy' value='$privacy'><td class='no-border'>$display_name</td><td>$object_rows</td><td>$privacy</td><td><button class='btn' onclick='addEntryFromIndex(this);'>Add</button></td><td><a href='#' class='btn table-btn table-view-btn'>View</a><td><a href='#' class='btn table-btn' onclick='sendObjectData(this);'>Edit</a></td><td><a href='#' class='btn table-btn delete-object-btn' onclick='confirmDeleteObject(this);'>Delete</a></td></tr>";
			            }
			        } else {
			        	if (isset($_POST['search-input'])) {
			        		$no_records_err = "Object name not found. <a href='index.php' class='error-msg'>Click to reload table.</a>";
			        	} else {
			        		$no_records_err = "You currently have no records in the database.";
			        	}
			        }

			        unset($_POST['search-input']);
			        mysqli_close($conn);
				?>

				</tbody>
			</table>
		</div>

		<p><?php echo $no_records_err; ?></p>

		<div class="btn-group"><a href="add-object-design.php" class="btn general-btn">Add New Object Design</a></div>

		<div id="edit-object-form-parent" class="popup-form-div">
			<form id="edit-object-form" class="popup-form" action="edit-object.php" method="POST">
			</form>
		</div>

		<div id="delete-object-form-parent" class="popup-form-div">
			<form id="delete-object-form-index" class="popup-form" action="delete-object.php" method="POST">
				<p>Are you sure you want to <span style="color:red;">PERMANENTLY</span> delete this object design and all of its contents from the database?</p> 
				<p><strong>WARNING: This action will be irreversible and you can not retrieve nor restore this data in the future.</strong></p>
			</form>
		</div>

<?php include 'footer.php' ?>