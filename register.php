<?php

require_once "config.php";

$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate email
    if(empty(trim($_POST["signup-email"]))){
        $email_err = "Please enter a username.";
    } else if (strlen(trim($_POST["signup-email"])) < 6 || strlen(trim($_POST["signup-email"])) > 14) {
        $email_err = "Username must be more than 6 characters and not more than 14 characters.";
    } else{
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
        	// Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            $param_email = trim($_POST["signup-email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // store result
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This username is already taken.";
                } else{
                    $email = trim($_POST["signup-email"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
                $_SESSION['registration_success'] = false;
            }
        }
       
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST["signup-pw"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["signup-pw"])) < 8){
        $password_err = "Password must have atleast 8 characters.";
    } else{
        $password = trim($_POST["signup-pw"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["signup-pw-2"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["signup-pw-2"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_email, $param_password);
            
            // Set parameters
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Create password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $_SESSION['registration_success'] = true;
                header('Location: register-success.php');
            } else{
            	$_SESSION['registration_success'] = false;
            	header('Location: '.$_SERVER['REQUEST_URI']);
            }

            // Close statement
        	mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($conn);
}

?>

<?php include 'header.php' ?>

		<div id="signup-div" class="form-div">
			<h2>Create an account:</h2>
			<form id="signup-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
				<input type="text" id="signup-email" name="signup-email" placeholder="Username" required>
				<span class="error-p"><?php echo $email_err; ?>

				<input type="password" id="signup-pw" name="signup-pw" placeholder="Password" required>
				<span class="error-p"><?php echo $password_err; ?>

				<input type="password" id="signup-pw-2" name="signup-pw-2" placeholder="Confirm Password" required>
				<span class="error-p"><?php echo $confirm_password_err; ?></span>

				<input type="submit" id="signup-submit" class="submit-btn" value="Create Account">
			</form>
		</div>
		<p class="center">Already have an account? <a href="login.php">Click here to login.</a></p>

<?php include 'footer.php' ?>