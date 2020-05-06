<?php
session_start();

require "server.php";

if(isset($_POST['login'])) {

    $username = $_POST['username'];

    $password = sha1($_POST['password']);
    $salt = md5("userlogin");
    $pepper = "asdfghjkl";
    $encrypted_password = $salt . $password . $pepper;

    $username = mysqli_real_escape_string($conn, $username);
    $encrypted_password = mysqli_real_escape_string($conn, $encrypted_password);
    $sql = "SELECT * FROM register WHERE username = '$username' AND password = '$encrypted_password'";
    $result = $conn->query($sql);

    if (mysqli_num_rows($result) > 0) {
        echo "Successfully logged in";
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $encrypted_password;
        while ($row = $result->fetch_assoc()) {
            $_SESSION['user_id'] = $row['id'];
        }
        header("Location:http://students.emps.ex.ac.uk/arjd201/Task.php");
    }
    else{
        echo 'Check your credentials!';
        header("Location:http://students.emps.ex.ac.uk/arjd201/SignUp.php");
    }

}

if(isset($_POST['register'])) {

    $username = $_POST['user'];
    $email = $_POST['email'];

    $password = sha1($_POST['psw']);
    $salt = md5("userlogin");
    $pepper = "asdfghjkl";
    $encrypted_password = $salt . $password . $pepper;

    //for sql injection protection
    $email = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT * FROM register WHERE email= '$email'";
    $result = $conn->query($sql);
    if (mysqli_num_rows($result) > 0) {
        echo "Email already exists!";
    }
    else{
        $username = mysqli_real_escape_string($conn, $username);
        $email = mysqli_real_escape_string($conn, $email);
        $encrypted_password = mysqli_real_escape_string($conn, $encrypted_password);
        $sql = "INSERT INTO register (username, email, password) VALUES ('$username', '$email', '$encrypted_password')";
        $result = $conn->query($sql);
        if($result){
            echo "you have successfully registered!";
            header("Location:http://students.emps.ex.ac.uk/arjd201/SignUp.php");
        }
        else{
            echo "Please try again";
            header("Location:http://students.emps.ex.ac.uk/arjd201/SignUp.php");
        }
    }

}
?>
