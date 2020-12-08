<?php
    //
    // CONNECTING TO DATABASE
    //

    // mysqli_connect('server', 'username', 'password', 'database_name')
	// 'localhost', 'admin', 'adminpass', 'tax'
    $conn = mysqli_connect('localhost', 'mytax3', '?=uIPq~,84x4', 'mytax3');

    if(!$conn){
        echo 'Connection error: ' . mysqli_connect_error();
    }
?>