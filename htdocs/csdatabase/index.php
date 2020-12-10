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
    $res_array = array();
    while($row = mysqli_fetch_assoc($res)){
        array_push($res_array, $row);
    }

    // free result from memory
    mysqli_free_result($res);
    // close connection
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
    
    <?php include('templates/header.php'); ?>
    
    <h4 class="center grey-text">Tax Return</h4>

    <div class="container">
        <div class="row">
            <?php foreach($res_array as $row){ ?>
                <div class="col s6">
                    <div class="card z-depth-0">
                        <div class="card-content center">
                            <h6>
                                <?php echo htmlspecialchars($row['TID']); ?>
                            </h6>
                            <div> 
                                <?php echo htmlspecialchars($row['FirstName']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include('templates/footer.php'); ?>

</html>