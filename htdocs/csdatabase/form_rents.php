<?php

    include('db_connect.php');  

    $renterID = $ownerID = $propertyID = $rentPerMonth = $startDate = '';
    $errors = array('renterID' => '', 'ownerID'=>'', 'propertyID'=>'', 'rentPerMonth'=>'', 'startDate'=>'');
    
    if(isset($_POST['submit'])){
        if(empty($_POST['renterID'])){
            $errors['renterID'] = 'A \'Renter ID\' is required <br />';
        }
        else{
            $renterID = $_POST['renterID'];
            if(!preg_match('/^[0-9]{20}$/', $renterID)){ //{#} is the length it will match
                $errors['renterID'] = 'Renter ID must only be numbers with a length of 20 <br />';
            }
        }
        
        if(empty($_POST['ownerID'])){
            $errors['ownerID'] = 'A \'Owner ID\' is required <br />';
        }
        else{
            $ownerID = $_POST['ownerID'];
            if(!preg_match('/^[0-9]{20}$/', $ownerID)){ //{#} is the length it will match
                $errors['ownerID'] = 'Owner ID must only be numbers with a length of 20 <br />';
            }
        }
        
        if(empty($_POST['propertyID'])){
            $errors['propertyID'] = 'A \'Property ID\' is required <br />';
        }
        else{
            $propertyID = $_POST['propertyID'];
            if(!preg_match('/^[0-9]{20}$/', $propertyID)){ //{#} is the length it will match
                $errors['propertyID'] = 'Property ID must only be numbers with a length of 20 <br />';
            }
        }

        if(empty($_POST['rentPerMonth'])){
            $errors['rentPerMonth'] = 'An \'Rent Per Month\' is required <br />';
        }
        else{
            $rentPerMonth = $_POST['rentPerMonth'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $rentPerMonth)){
                $errors['rentPerMonth'] = 'Rent Per Month must contain only numbers <br />';
            }
        }

        if(empty($_POST['startDate'])){
            $errors['startDate'] = 'A \'Date\' is required <br />';
        }
        else{
            $startDate = $_POST['startDate'];
            if(!preg_match('/^([0-9]{4}-[0-9]{1,2}-[0-9]{1,2})$/', $startDate)){
                $errors['startDate'] = 'Date must be in format YYYY-MM-DD <br />';
            }
        }

        if(array_filter($errors)){
            //echo 'errors';
        }
        else{

            $renterID = mysqli_real_escape_string($conn, $_POST['renterID']);
            $ownerID = mysqli_real_escape_string($conn, $_POST['ownerID']);
            $propertyID = mysqli_real_escape_string($conn, $_POST['propertyID']);
            $rentPerMonth = mysqli_real_escape_string($conn, $_POST['rentPerMonth']);
            $startDate = mysqli_real_escape_string($conn, $_POST['startDate']);



            // create sql
            $sql = "INSERT INTO rents(RenterID, OwnerID, PropertyID, RentPerMonth, StartDate)
                    VALUES('$renterID', '$ownerID', '$propertyID', '$rentPerMonth', '$startDate' )";

            // save to database and check if it was sucessfull
            if(mysqli_query($conn, $sql)){
                // success

                // redirect user to user page
                header('Location: user.php');
            }
            else{
                // failed
                echo 'query error: ' . mysqli_error($conn);
            }
        }
    }  // end of post check
?>

<!DOCTYPE html>
<html>

    <?php include('templates/header.php'); ?>

    <section class="container grey-text">
        <h4 class="center">Rental Properties</h4>
        <form class="white" action="form_rents.php" method="POST">
            <label>Renter ID </label>
            <input type="text" name="renterID" value="<?php echo htmlspecialchars($renterID)?>">
            <div class="red-text"><?php echo $errors['renterID']; ?></div>
            
            <label>Owner ID</label>
            <input type="text" name="ownerID" value="<?php echo htmlspecialchars($ownerID)?>">
            <div class="red-text"><?php echo $errors['ownerID']; ?></div>
            
            <label>Property ID</label>
            <input type="text" name="propertyID" value="<?php echo htmlspecialchars($propertyID)?>">
            <div class="red-text"><?php echo $errors['propertyID']; ?></div>
            
            <label>Rent Per Month</label>
            <input type="text" name="rentPerMonth" value="<?php echo htmlspecialchars($rentPerMonth)?>">
            <div class="red-text"><?php echo $errors['rentPerMonth']; ?></div>
            
            <label>Start Date</label>
            <input type="text" name="startDate" value="<?php echo htmlspecialchars($startDate)?>">
            <div class="red-text"><?php echo $errors['startDate']; ?></div>
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>