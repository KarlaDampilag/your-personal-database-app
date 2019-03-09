<?php
require_once 'config.php';

//If the user is not logged in, redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

?>

<?php include 'header.php' ?>

<?php

$objectName = str_replace(' ', '_', strtolower($_POST['object-name']));
$attributes = json_decode($_POST['attributes']);
$privacy = $_POST['privacy'];
$user_id = $_SESSION['id'];
$dbObjectName = str_replace(' ', '_', strtolower($_POST['object-name']))."_".$user_id;

$test = "SELECT * FROM object_designs WHERE user_id = $user_id AND name = '$objectName'";

$result = mysqli_query($conn, $test);

if (mysqli_num_rows($result) > 0) {
    echo "<p>Unable to save - you already have an object named ".$_POST['object-name']." in our database.</p><a class='btn' href='add-object-design.php'>Back</a>";
} else {
    $sql = "INSERT INTO object_designs (name, privacy, user_id) VALUES ('$objectName', '$privacy', $user_id)";

    if ($conn->query($sql) === TRUE) {
        $sql2 = "CREATE TABLE $dbObjectName (id INT(6) AUTO_INCREMENT PRIMARY KEY, object_designs_id INT(6)";

        for($i = 0; $i < count($attributes); $i++) {
            $sql2 .= ', ' . str_replace(' ', '_', strtolower($attributes[$i])) . ' VARCHAR(250) NOT NULL';
        }

        $sql2 .= ')';

        if ($conn->query($sql2) === TRUE) {
            header('Location: index.php');
            exit;

        } else {
            echo "Error code 2: Error creating object: " . $conn->error;
        }
    } else {
        echo "Error code 1: Error creating object: " . $conn->error;
    }
}

mysqli_close($conn);

?>
<?php include 'footer.php' ?>