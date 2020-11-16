<?php
    //
    // CONNECTING TO DATABASE
    //

    // mysqli_connect('server', 'username', 'password', 'database_name')
    $conn = mysqli_connect('localhost', 'admin', 'adminpass', 'tax');

    if(!$conn){
        echo 'Connection error: ' . mysqli_connect_error();
    }

    // write query
    $sql = 'SELECT TID, FirstName FROM taxpayer';
    // make query and get results
    $results = mysqli_query($conn, $sql);
    // store results into an array
    $test = mysqli_fetch_all($results, MYSQLI_ASSOC);
    // free result from memory
    mysqli_free_result($results);
    // close connection
    mysqli_close($conn);
    print_r($test);

    //print_r($test);
?>

<!DOCTYPE html>
<html>
    
    <?php include('templates/header.php'); ?>
    
    <?php include('templates/footer.php'); ?>

</html>