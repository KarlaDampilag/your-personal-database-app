<?php
	require_once 'config.php';

	//If the user is not logged in, redirect to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: login.php");
	    exit;
	}

	$no_records_err = $add_btn = "";
?>

<?php include 'header.php' ?>

<div class="lg-form-div form-div">
	<div class="btn-group">
		<a href="index.php"><button type="button" class="btn no-shadow">Back To Homepage</button></a>
	</div>

	<h2 class="page-title">Design your "Object"</h2>

	<form method="POST" id="design-obj-form">
		<div class="form-section">
			<p>Hint: people usually create objects such as...</p>

			<ul>
				<li>People</li>
				<li>Books</li>
				<li>To Do List</li>
				<li>Events</li>
				<li>Shopping List</li>
				<li>and so on</li>
			</ul>

			<p>It could be anything you want. What "thing" do you want to keep a record of? Give it a name below:</p>

			<label>Object name:</label><input type="text" name="object-name" id="object-name" placeholder="E.g. Books" required>
		</div>
		<div class="form-section inline-section">
			<h3>Object Attributes</h3>

			<p>An object has attributes. For example, a person has a first name, last name, birth date, etc. A book has a title, author, genre, and so on.</p>

			<p>With your object in mind, give it attriutes using the form below. Add only the attributes that are necessary (attributes you want to keep records of):</p>

			<div id="object-att-form">
				<label>Attribute name:<input type="text" id="att-1" name="attribute" class="attribute-input" placeholder="E.g. Title" required></label>
			</div>
			<button type="button" class="inside-form-btn btn" onclick="addAttField();">Add 1 More Attribute</button>
		</div>
		<div class="form-section">
			<h3>Object Privacy</h3>
			<label><input type="radio" name="privacy" value="public" checked="checked" required> Public <span style="font-weight:normal;"> - other people can view its records (contents), but only you can edit or delete.</span></label>
			<label><input type="radio" name="privacy" value="private" id="private-radio-btn"> Private <span style="font-weight:normal;"> - only you can view its records.</span></label>
		</div>

		<p>Before saving, you must first preview your object design:</p>

		<div class="btn-group two-cols">
			<button type="submit" class="btn">Preview Object Design</button>
			<a href="index.php"><button type="button" class="btn">Cancel</button></a>
		</div>
	</form>
</div>

<p style="margin-top: 100px;"><a href="logout.php">Log Out</a></p>
<p><a href="reset-password.php">Reset Password</a></p>

<?php include 'footer.php' ?>