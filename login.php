<?php
require_once 'config.php';

// If the user is already logged in, redirect to the home page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	header("location: index.php");
	exit;
}

$email = $password = "";
$err = $email_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["login-email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["login-email"]);
    }
    
    if(empty(trim($_POST["login-pw"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["login-pw"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    

                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $email;                            
                            
                            header("Location: index.php");
                        } else{
                            $err = "Invalid email or password.";
                        }
                    }
                } else{
                    $err = "Invalid email or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
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

		<div id="login-div" class="form-div">
			<h2>Login:</h2>
			<form id="login-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
				<input type="text" id="login-email" name="login-email" placeholder="Username" required>
				<span class="error-p"><?php echo $email_err; ?>

				<input type="password" id="login-pw" name="login-pw" placeholder="Password" required>
				<span class="error-p"><?php echo $password_err; ?>

				<input type="submit" id="login-submit" class="submit-btn" value="Log In">
				<p class="error-p"><?php echo $err; ?></p>
			</form>
		</div>
		<p class="center">Don't have an account? <a href="register.php">Click here to sign up.</a></p>

<?php include 'footer.php' ?>