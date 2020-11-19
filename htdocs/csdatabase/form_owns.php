<?php

    include('db_connect.php');  

    $ownerID = $homeID ='';
    $errors = array('ownerID' => '', 'homeID'=>'');

    if(isset($_POST['submit'])){
        if(empty($_POST['ownerID'])){
            $errors['ownerID'] = 'A \'Owner ID\' is required <br />';
        }
        else{
            $ownerID = $_POST['ownerID'];
            if(!preg_match('/^[0-9]{20}$/', $ownerID)){ //{#} is the length it will match
                $errors['ownerID'] = 'Owner ID must only be numbers with a length of 20 <br />';
            }
        }

        if(empty($_POST['homeID'])){
            $errors['homeID'] = 'A \'Home ID\' is required <br />';
        }
        else{
            $homeID = $_POST['homeID'];
            if(!preg_match('/^[0-9]{20}$/', $homeID)){ //{#} is the length it will match
                $errors['homeID'] = 'Home ID must only be numbers with a length of 20 <br />';
            }
        }

        if(array_filter($errors)){
            //echo 'errors';
        }
        else{
            $ownerID = mysqli_real_escape_string($conn, $_POST['ownerID']);
            $homeID = mysqli_real_escape_string($conn, $_POST['homeID']);

            // create sql
            $sql = "INSERT INTO owns(OID, HomeID)
                    VALUES('$ownerID', '$homeID')";

            // save to database and check if it was sucessfull
            if(mysqli_query($conn, $sql)){
                // success

                // redirect user to user page
                header('Location: company.php');
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
        <h4 class="center">Owns</h4>
        <form class="white" action="form_owns.php" method="POST">
            <label>Owner ID </label>
            <input type="text" name="ownerID" value="<?php echo htmlspecialchars($ownerID)?>">
            <div class="red-text"><?php echo $errors['ownerID']; ?></div>
            
            <label>Home ID</label>
            <input type="text" name="homeID" value="<?php echo htmlspecialchars($homeID)?>">
            <div class="red-text"><?php echo $errors['homeID']; ?></div>
            
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>