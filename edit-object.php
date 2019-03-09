<?php
	require_once 'config.php';

	//If the user is not logged in, redirect to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: login.php");
	    exit;
	}

	$obj_name = $obj_privacy = $obj_design_id = $display_obj_name = "";

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

	$holder = explode('_', $obj_name);

	for ($i = 0; $i < count($holder); $i++){
		if ($i != count($holder)-1) {
			$display_obj_name .= $holder[$i] . " ";
		}
	}

	$display_obj_name = $_SESSION['display-name'] = ucwords($display_obj_name);
?>

<?php include 'header.php' ?>

<h2>Edit Design of Object: <?php echo $_SESSION['display-name'] ?></h2>

<a href="view-records.php"><button type='button' class="btn">Back to records</button></a>

<div class="white-div">
	<strong><p>Choose an appropriate tab:</p></strong>

	<ul class="tabs">
		<a href="#" onclick="showTabOption(1);"><li class="selected tab">Rename Column Name/s</li></a>
		<a href="#" onclick="showTabOption(2);"><li class="tab">Add Column</li></a>
		<a href="#" onclick="showTabOption(3);"><li class="tab">Delete Column</li></a>
		<a href="#" onclick="showTabOption(4);"><li class="tab">Change Privacy</li></a>
	</ul>
	<div id="rename-div" class="tab-options">
		<form id="rename-object-columns-form" class="block" method="POST" action="rename-object-column.php">

		<?php
			$sql_get = "DESCRIBE $obj_name";
			$result_get = mysqli_query($conn, $sql_get);

			if (mysqli_num_rows($result_get) > 0) {
				$counter = 0;

				while ($row = mysqli_fetch_assoc($result_get)) {
					$col_name = $row['Field'];

					if ($col_name != 'id' && $col_name != 'object_designs_id') {
						$counter++;
						$display_col = ucfirst(str_replace('_', ' ', $col_name));
		?>
						<label>Column <?php echo $counter.': ';?><input type="text" name="<?php echo $col_name; ?>" value="<?php echo $display_col; ?>" required></label>
		<?php
					}
				}
			}

		?>
			<div class="btn-group">
				<button type="button" class="btn" onclick="clearForm('rename-object-columns-form');">Clear Form</button>
				<button type="button" class="btn" onclick="location.reload();">Reload Original Columns</button>
				<button type="button" class="btn" onclick="confirmAction();">Save Changes</button>
			</div>
		</form>
	</div>
	<div id="add-div" class="tab-options block">
		<form id="add-object-column-form" action="add-object-column.php" method="POST">
			<input type="hidden" name="object-name" value="<?php echo $obj_name; ?>">
			<div class="bordered-holder">
				<div id="new-column-inputs">
						<label>New column name: <input type="text" name="new-col-1" class="new-col" placeholder="E.g. Author" required><button type="button" class="btn" onclick="deleteParentField(this);">Remove</button></label>
				</div>
				<button type="button" class="btn" onclick="addColumnField();">Add one more column</button>
			</div>
			<div class="btn-group">
				<button type="submit" class="btn">Save Changes</button>
			</div>
		</form>
	</div>
	<div id="delete-div" class="tab-options">
		<?php
			$sql_get = "DESCRIBE $obj_name";
			$result_get = mysqli_query($conn, $sql_get);

			if (mysqli_num_rows($result_get) > 0) {
		?>
				<table>
					<thead>
						<tr><th>Column Name</th><th>Delete</th></tr>
					</thead>
					<tbody>
				<?php
					while ($row = mysqli_fetch_assoc($result_get)) {
						$col_name = $row['Field'];

						if ($col_name != 'id' && $col_name != 'object_designs_id') {
							$display_col = ucfirst(str_replace('_', ' ', $col_name));
				?>
							<tr id="<?php echo $col_name; ?>">
								<td><?php echo $display_col; ?></td>
								<td><button type="button" class="btn" onclick="confirmDeleteObjectColumn(<?php echo "'$col_name'"; ?>);">Delete</button></td>
							</tr>
				<?php
						}
					}
				?>
					</tbody>
				</table>
		<?php
			}
		?>
		
	</div>
	<div id="privacy-div" class="tab-options">
		<form id="change-privacy-form" action="change-object-privacy.php" method="POST">
			<?php
				if ($obj_privacy == 'public') {
			?>
			
			<input type="radio" name="privacy" value="public" checked="checked">Public
			<input type="radio" name="privacy" value="private">Private

			<?php
				} else {
			?>

			<input type="radio" name="privacy" value="public">Public
			<input type="radio" name="privacy" value="private" checked="checked">Private

			<?php
				}
			?>

			<div class="btn-group">
				<input type="submit" class="btn" value="Save">
			</div>
		</form>
	</div>
</div>

<a href="view-records.php"><button type='button' class="btn">Back to records</button></a>

<div id="confirm-action-div" class="popup-form-div">
	<form class="popup-form">
		<span>Are you sure?</span><br/>
		<div class="btn-group">
			<button type="button" class="btn" onclick="editObjectColumnNames();">Yes</button>
			<button type="button" class="btn" onclick="this.parentNode.parentNode.parentNode.style.display = 'none';">No</button>
		</div>
	</form>
</div>

<div id="confirm-delete-object-column-parent" class="popup-form-div">
	<form id="confirm-delete-object-column-form" class="popup-form" action="delete-object-column.php" method="POST">
		<input type="hidden" name="object-name" value="<?php echo $obj_name ?>">

		<p>Are you sure you want to <span style="color:red;">PERMANENTLY</span> delete this column and all of its contents from the database?</p> 
		<p><strong>WARNING: This action will be irreversible and you can not retrieve nor restore this data in the future.</strong></p>
	</form>
</div>

<?php include 'footer.php' ?>