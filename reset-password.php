<?php
require_once 'config.php';

//If the user is not logged in, redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new password
    if(empty(trim($_POST["reset-pw"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["reset-pw"])) < 8){
        $new_password_err = "Password must have atleast 8 characters.";
    } else{
        $new_password = trim($_POST["reset-pw"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["reset-pw-2"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["reset-pw-2"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("Location: change-password-success.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($conn);
}

?>

<?php include 'header.php' ?>

		<div id="reset-pw-div" class="form-div">
			<h2>Reset Password:</h2>
			<form id="reset-pw-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
				<input type="password" id="reset-pw" name="reset-pw" placeholder="New Password" required>
				<span class="error-p"><?php echo $new_password_err; ?></span>

				<input type="password" id="reset-pw-2" name="reset-pw-2" placeholder="Confirm New Password" required>
				<span class="error-p"><?php echo $confirm_password_err; ?></span>

				<input type="submit" id="login-submit" class="submit-btn" value="Reset">
			</form>
		</div>
		<p class="center"><a href="index.php">Go back to home page</a></p>

<?php include 'footer.php' ?>