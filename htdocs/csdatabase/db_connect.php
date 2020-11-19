<?php
    //
    // CONNECTING TO DATABASE
    //

    // mysqli_connect('server', 'username', 'password', 'database_name')
    $conn = mysqli_connect('localhost', 'admin', 'adminpass', 'tax');

    if(!$conn){
        echo 'Connection error: ' . mysqli_connect_error();
    }
?>