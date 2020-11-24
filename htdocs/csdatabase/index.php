<?php

    include('db_connect.php');

    // write query
    $sql = 'SELECT TID, FirstName FROM taxpayer';
    // make query and get results
    $res = mysqli_query($conn, $sql);
    // store results into an array
    $results = mysqli_fetch_all($res, MYSQLI_ASSOC);
    // free result from memory
    mysqli_free_result($res);
    // close connection
    mysqli_close($conn);
    //print_r($results);

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
                            </h6>
                            <div><?php echo htmlspecialchars($result['FirstName']); ?></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php include('templates/footer.php'); ?>

</html>