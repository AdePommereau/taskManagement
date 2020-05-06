<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define(host,"***********");
define(username,"*********");
define(password,"******");
define(database,"*******");
define(port,3306);


$conn = new mysqli(host, username, password, database, port);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
