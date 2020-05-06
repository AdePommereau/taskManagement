<?php

session_start();

require "server.php";

$username = $_SESSION['username'];
$password = $_SESSION['password'];
$user_id = $_SESSION['user_id'];

if(isset($_POST['new'])) {

    if(isset($_POST['task_name'])) {
        $name = $_POST['task_name'];
    }
    if(isset($_POST['description'])) {
        $description = $_POST['description'];
    }
    if(isset($_POST['date'])) {
        $date = $_POST['date'];
        $new_date = preg_replace("/T/", " ", $date);
    }

    $email = mysqli_real_escape_string($conn, $email);
    $description = mysqli_real_escape_string($conn, $description);
    $new_date = mysqli_real_escape_string($conn, $new_date);
    $name = mysqli_real_escape_string($conn, $name);
    $sql = "INSERT INTO tasks (username, description, due_date, task_name, task_state) VALUES ('$username', '$description', '$new_date', '$name', 'Not done')";

    if($conn->query($sql)){
        echo "Task successfully added";
        header('Location: http://students.emps.ex.ac.uk/arjd201/Task.php');
    }
    else{
        echo "Please try again later";
    }

}


if(isset($_POST['remove'])){
    $task_id = $_POST['remove'];

    $username = mysqli_real_escape_string($conn, $username);
    $task_id = mysqli_real_escape_string($conn, $task_id);
    $sql = "DELETE FROM tasks WHERE username = '$username' AND task_id = '$task_id'";

    if($conn->query($sql)){
        echo "Task removed";
    }
    else{
        echo "Please try again later";
    }

}

if(isset($_POST['log_out'])) {
    session_destroy();
}

if(isset($_POST['changeUser'])){

    $new_username = $_POST['new_username'];

    $new_username = mysqli_real_escape_string($conn, $new_username);
    $username = mysqli_real_escape_string($conn, $username);
    $user_id = mysqli_real_escape_string($conn, $user_id);

    $sql = "SELECT * FROM tasks WHERE username = '$username'";
    $result = $conn->query($sql);
    if(mysqli_num_rows($result) > 0){
        $sql = "UPDATE register, tasks SET register.username = '$new_username', tasks.username = '$new_username' WHERE register.username = '$username' AND register.id = '$user_id' AND tasks.username = '$username'";
        if($conn->query($sql)){
            echo "Username updated";
            $_SESSION['username'] = $_POST['new_username'];
            $username = $_SESSION['username'];
            header('Location: http://students.emps.ex.ac.uk/arjd201/Task.php');
        }
    }
    else{
        $sql = "UPDATE register SET username = '$new_username' WHERE username = '$username' AND id = '$user_id'";
        if($conn->query($sql)){
            echo "Username updated";
            $_SESSION['username'] = $_POST['new_username'];
            $username = $_SESSION['username'];
            header('Location: http://students.emps.ex.ac.uk/arjd201/Task.php');
        }
    }


}

if(isset($_POST['changeEmail'])){

    $new_email = $_POST['new_email'];

    $new_email = mysqli_real_escape_string($conn, $new_email);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $sql = "UPDATE register SET email = '$new_email' WHERE username = '$username' AND id = '$user_id'";
    if($conn->query($sql)){
        echo "Email updated";
        header('Location: http://students.emps.ex.ac.uk/arjd201/Task.php');
    }
}

if(isset($_POST['changePassword'])){

    $new_password = sha1($_POST['new_password']);
    $salt = md5("userlogin");
    $pepper = "asdfghjkl";
    $new_encrypted_password = $salt . $new_password . $pepper;

    $new_encrypted_password = mysqli_real_escape_string($conn, $new_encrypted_password);
    $username = mysqli_real_escape_string($conn, $username);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $sql = "UPDATE register SET password = '$new_encrypted_password' WHERE username = '$username' AND id = '$user_id'";
    if($conn->query($sql)){
        echo "Password updated";
        $_SESSION['password'] = $new_encrypted_password;
        $password = $_SESSION['password'];
        header('Location: http://students.emps.ex.ac.uk/arjd201/Task.php');
    }
}

if(isset($_POST['edit_task']) && isset($_POST['description_edit']) && isset($_POST['date_edit']) && isset($_POST['name_edit'])){

    $description = $_POST['edit_task'];
    $new_description = $_POST['description_edit'];
    $new_date = $_POST['date_edit'];
    $new_name = $_POST['name_edit'];

    $new_description = mysqli_real_escape_string($conn, $new_description);
    $new_date = mysqli_real_escape_string($conn, $new_date);
    $username = mysqli_real_escape_string($conn, $username);
    $description = mysqli_real_escape_string($conn, $description);
    $sql = "UPDATE tasks SET description = '$new_description', due_date = '$new_date', task_name = '$new_name' WHERE username = '$username' and description = '$description'";
    if($conn->query($sql)){
        echo "Task updated";
        header('Location: http://students.emps.ex.ac.uk/arjd201/Task.php');
    }
    else{
        echo "Please try again later";
    }
}

if(isset($_POST['import_selected'])){
    if ($_POST['check']){
        foreach ($_POST['check'] as $item){
            $task = explode (", ", $item);
            $task_name = $task[0];
            $task_description = $task[1];
            $task_due_date = $task[2];
            $id = $task[3];

            $username = mysqli_real_escape_string($conn, $username);
            $task_description = mysqli_real_escape_string($conn, $task_description);
            $task_due_date = mysqli_real_escape_string($conn, $task_due_date);
            $task_name = mysqli_real_escape_string($conn, $task_name);
            $id = mysqli_real_escape_string($conn, $id);
            $sql = "INSERT INTO tasks (username, description, due_date, task_name, task_state, api_task_state) VALUES ('$username', '$task_description', '$task_due_date', '$task_name', 'Not done', '$id')";

            if($conn->query($sql)){
                echo "Import successfull";
                header('Location: http://students.emps.ex.ac.uk/arjd201/Task.php');
            }
            else{
                echo "There was a problem importing the tasks.";
            }

        }
    }
}

if (isset($_POST['edit'])){

    $task_id = $_POST['edit'];

    if(isset($_POST['edit_name'])){
        $new_name = $_POST['edit_name'];
    }
    if(isset($_POST['description'])){
        $new_description = $_POST['description'];
    }
    if(isset($_POST['date'])){
        $new_date = $_POST['date'];
    }

    $new_description = mysqli_real_escape_string($conn, $new_description);
    $new_date = mysqli_real_escape_string($conn, $new_date);
    $new_name = mysqli_real_escape_string($conn, $new_name);
    $username = mysqli_real_escape_string($conn, $username);
    $task_id = mysqli_real_escape_string($conn, $task_id);
    $sql = "UPDATE tasks SET description = '$new_description', due_date = '$new_date', task_name = '$new_name' WHERE username = '$username' AND task_id = '$task_id'";
    if($conn->query($sql)){
        echo "Edit successful";
        header('Location: http://students.emps.ex.ac.uk/arjd201/Task.php');
    }
    else{
        echo "Please try again later";
    }
}

if(isset($_POST['export'])){

    if(isset($_POST['edit_name'])){
        $new_name = $_POST['edit_name'];
    }
    if(isset($_POST['description'])){
        $new_description = $_POST['description'];
    }
    if(isset($_POST['date'])){
        $new_date = $_POST['date'];
    }

    $myXMLBody = "<taskinfo>
                      <name>$new_name</name>
                      <due>$new_date</due>
                      <description>$description</description>
                      </taskinfo>";
    $context = stream_context_create(array('http'=>array(
        'method'=>'POST',
        'content' =>  $myXMLBody
    )));
    $sendData = file_get_contents('http://students.emps.ex.ac.uk/dm656/add.php', false, $context);
    header('Location: http://students.emps.ex.ac.uk/arjd201/Task.php');
}
