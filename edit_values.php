<?php

require "server.php";

session_start();

$username = $_SESSION['username'];
$task_id = $_POST['task_id'];

$sql = "SELECT * FROM tasks where username= '$username' AND task_id = '$task_id'";

$response = "<div class='container'>";
$response .= "<h2 class='mod'>Edit task:</h2>";

$row = mysqli_fetch_array($conn->query($sql));

$name = $row['task_name'];
$description = $row['description'];
$date = $row['due_date'];
$formatted_date = substr($date,0, 10).'T'.substr($date, 10, 16);
$formatted_date = str_replace(' ', '', $formatted_date);

$response .=  "<form method='post' action='task_controller.php'>";
$response .= "<div><label for='edit_name'><b>Name</b></label><textarea name='edit_name'>$name</textarea></div>";
$response .= "<div><label for='description'><b>Description</b></label><textarea name='description'>$description</textarea></div>";
$response .= "<br>";
$response .= "<div class=''wrap'><div class='subwrap'><label for='datepick' name='datelabel'><b>Due date:</b></label><input type='datetime-local' name='date' value='".$formatted_date."'></div></div>";
$response .= "<div class=''wrap'><div style='margin-top: 10px; text-align:center'><button style='margin-right:10px' value='" .$task_id. "' type='submit' name='edit'>Edit</button><button style='margin-right:10px' type='submit' name='export' onclick='Confirm()'>Export</button></div></div>";
$response .= "</form>";

$response .= "</div>";
echo $response;
exit;

?>

<html>
    <script>
        function Confirm(){
            confirm("Are you sure?");
        }
    </script>
</html>


