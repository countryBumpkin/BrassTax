<!-- <?php 
	echo 'Current PHP Version: ' . phpversion();
	//phpinfo();
    print_r(get_defined_functions());

?>
-->

<?php

    include('db_connect.php');

    // write query
    $sql = 'SELECT TID, FirstName FROM taxpayer';
    // make query and get results
    $res = mysqli_query($conn, $sql);
    // store results into an array
    $results = mysqli_fetch_array($res, MYSQLI_ASSOC);
    //$results = $res->fetch_assoc() // alternative to msqli functions


    printf("size of results = " + sizeof($results) + "\n");
    foreach($results as $result){
        echo htmlspecialchars($results["TID"]);
        echo htmlspecialchars($results["FirstName"]);
    }

    // free result from memory
    mysqli_free_result($res);
    // close connection
    mysqli_close($conn);

    //print_r($results);

?>

<!DOCTYPE html>
<html>
    
    <?php include('templates/header.php'); ?>
    
    <h4 class="center grey-text">Tax Return</h4>

    <div class="container">
        <div class="row">
            <?php foreach($results as $result){ ?>
                <div class="col s6">
                    <div class="card z-depth-0">
                        <div class="card-content center">
                            <h6>
                                <?php echo htmlspecialchars($result['TID']); ?>
                                <!-- <?php printf("%u", $result['TID']); ?> -->
                            </h6>
                            <div><!-- <?php echo htmlspecialchars($result['FirstName']); ?> -->
                                <?php printf("%s", $result['FirstName']); ?> 
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include('templates/footer.php'); ?>

</html>