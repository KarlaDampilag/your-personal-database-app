<?php
	require_once 'config.php';

	//If the user is not logged in, redirect to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	    header("location: login.php");
	    exit;
	}
?>

<?php include 'header.php' ?>

<h2 class="page-title">Preview Your Object Design</h2>

<h3 id="object-name-preview">Object Name: </h3>
<h3 id="privacy-preview">Privacy: </h3>

<table>
	<thead id="preview-head"></thead>
	<tbody id="preview-body"></tbody>
</table>

<p>The table above shows how your object design will look like when sample records are presented in a table.</p>

<p>Before saving your object design, double check everything, such as:</p>

<ul>
	<li>Did you add all the necessary attributes or columns that you wish to keep records of?</li>
	<li>Did you spell the attributes or columns correctly?</li>
	<li>Did you give your object a proper name?</li>
	<li>And so on.</li>
</ul>

<p>If you're happy with your design, click <strong>Save</strong> to finalize and start adding records to it. Otherwise, click <strong>Edit</strong> to make some changes.</p>

<div class="btn-group">
	<a href="add-object-design.php" class="btn">Edit</a>
	<a href="#" class="btn" onclick="saveDesignToDb();">Save</a>
</div>

<p style="margin-top: 100px;"><a href="logout.php">Log Out</a></p>
<p><a href="reset-password.php">Reset Password</a></p>

<?php include 'footer.php' ?>