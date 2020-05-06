<?php

require "server.php";

session_start();

$username = $_SESSION['username'];
$task_id = $_POST['task_id'];


$sql= "SELECT * FROM tasks WHERE username = '$username' AND task_id = '$task_id'";
if($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $api_id = $row['api_task_state'];
        //check state of task
        if ($row['task_state'] == 'Not done') {
            $sql = "UPDATE tasks SET task_state = 'Done' WHERE username = '$username' AND task_id = '$task_id'";
            $result = $conn->query($sql);
            header('Location: http://students.emps.ex.ac.uk/arjd201/Task.php');

            if (!empty($api_id)) {
                $state_xml = "<user>
                                <id>$api_id</id>
                              </user>";
                $state_url = "http://students.emps.ex.ac.uk/dm656/check.php/" . $api_id;
                $curl = curl_init($state_url);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
                curl_setopt($curl, CURLOPT_URL, $state_url);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $state_xml);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 300);
                $curl_result = curl_exec($curl);
                $response = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($state_url);

                if ($response == '409') {
                    //pop up to notify the user that the task has been done before him and delete it from the database
                    echo "<script type='text/javascript'>confirm('The task " . $row['task_name'] . " has already been done by someone else!')</script>";
                    $sql = "DELETE FROM tasks WHERE username = '$username' AND api_task_state = '$api_id'";
                    $result = $conn->query($sql);
                    header('Location: http://students.emps.ex.ac.uk/arjd201/Task.php');
                }
            }
        }
        //uncheck state of task
        else {
            $sql = "UPDATE tasks SET task_state = 'Not done' WHERE username = '$username' AND task_id = '$task_id'";
            $result = $conn->query($sql);
            header('Location: http://students.emps.ex.ac.uk/arjd201/Task.php');
            if (!empty($api_id)) {
                $state_xml = "<user>
                                    <id>$api_id</id>
                                  </user>";
                $state_url = "http://students.emps.ex.ac.uk/dm656/uncheck.php/" . $api_id;
                $curl = curl_init($state_url);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
                curl_setopt($curl, CURLOPT_URL, $state_url);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $state_xml);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 300);
                $curl_result = curl_exec($curl);
                $response = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($state_url);
            }
        }
    }
}
