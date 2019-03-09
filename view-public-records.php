<?php
	require_once 'config.php';

	$no_records_err = $add_btn = "";
	$object_design_id = $object_name = "";
?>

<?php include 'header.php' ?>

		<h2>List of public records:</h2>

		<p>These are records that are made public by their owners, which means you may view its contents. That said, only owners can add, edit, delete their own records and its contents.</p>

		<div class="table-filters">
			<form id="table-filters-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
				<input type="text" name="search-input" placeholder="Object name" required>
				<input type="submit" class="btn no-shadow" value="Search">
				<a href="index.php"><input type="button" class="btn no-shadow" value="Reload Table"></a>
			</form>
		</div>

		<div class="table-holder">
			<table id="public-records-table" class="records-table">
				<thead>
					<tr>
						<th class="no-border">Object Name<div class="icon-holder"><i class="icon-up-dir" onclick="sortTable('public-records-table', 1, 'desc');"></i><i class="icon-down-dir" onclick="sortTable('public-records-table', 1, 'asc');"></i></div></th>
						<th>No. of Entries<div class="icon-holder"><i class="icon-up-dir" onclick="sortTable('public-records-table', 2, 'asc');"></i><i class="icon-down-dir" onclick="sortTable('public-records-table', 2, 'desc');"></i></div></th>
						<th>Owner<div class="icon-holder"><i class="icon-up-dir" onclick="sortTable('public-records-table', 3, 'asc');"></i><i class="icon-down-dir" onclick="sortTable('public-records-table', 3, 'desc');"></i></div></th>
						<th>View Contents</th>
					</tr>
				</thead>
				<tbody>

				<?php
		        	$sql = "SELECT * FROM object_designs WHERE privacy = 'public'";

		        	if (isset($_POST['search-input']) && $_POST['search-input'] != '') {
			            $search_input = $_POST['search-input'];
			            $sql .= " AND name='$search_input'";
			        }
			        
		        	$result = mysqli_query($conn, $sql);

		        	if (mysqli_num_rows($result) > 0) {
			            while($row = mysqli_fetch_assoc($result)) {
			            	$object_id = $object_design_id = $row['id'];
			            	$user_id = $row['user_id'];
			            	$object_name = strtolower($row['name']).'_'.$user_id;
			            	$display_name = ucwords(str_replace('_', ' ', $row["name"]));

			            	$sql2 = "SELECT * FROM $object_name WHERE object_designs_id = $object_id";

			            	$result2 = mysqli_query($conn, $sql2);

			            	$object_rows = mysqli_num_rows($result2);

			            	$sql3 = "SELECT username FROM users WHERE id=$user_id";

			            	$result3 = mysqli_query($conn, $sql3);

			            	$user_row = mysqli_fetch_assoc($result3);
			            	$username = $user_row['username'];

			            	echo "<tr id='$object_name'><input type='hidden' name='object-design-id' value='$object_id'><td class='no-border'>$display_name</td><td>$object_rows</td><td>$username</td><td><a href='#' class='btn table-btn table-view-btn'>View</a></tr>";
			            }
			        } else {
			        	if (isset($_POST['search-input'])) {
			        		$no_records_err = "Object name not found. <a href='index.php' class='error-msg'>Click to reload table.</a>";
			        	} else {
			        		$no_records_err = "There are currently no public records in the database. Why don't you create your own? <a href='register.php'>Click here to create a free account!</a>";
			        	}
			        }

			        unset($_POST['search-input']);
			        mysqli_close($conn);
				?>

				</tbody>
			</table>
		</div>

		<p><?php echo $no_records_err; ?></p>

<?php include 'footer.php' ?>