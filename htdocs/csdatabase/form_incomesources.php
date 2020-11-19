<?php

    include('db_connect.php');  

    $SID = $address = $contactPerson = '';
    $errors = array('SID' => '', 'address'=>'', 'contactPerson'=>'');
    
    if(isset($_POST['submit'])){
        if(empty($_POST['SID'])){
            $errors['SID'] = 'A \'SID\' is required <br />';
        }
        else{
            $SID = $_POST['SID'];
            if(!preg_match('/^[0-9]{20}$/', $SID)){ //{#} is the length it will match
                $errors['SID'] = 'SID must only be numbers with a length of 20 <br />';
            }
        }
        
        if(empty($_POST['address'])){
            $errors['address'] = 'A \'Address\' is required <br />';
        }
        else{
            $address = $_POST['address'];
            if(!preg_match('/^[a-zA-Z0-9\s]+$/', $address)){
                $errors['address'] = 'Address must only contain letters, numbers, and spaces<br />';
            }
        }

        if(empty($_POST['contactPerson'])){
            $errors['contactPerson'] = 'A \'Contact Person\' is required <br />';
        }
        else{
            $contactPerson = $_POST['contactPerson'];
            if(!preg_match('/^[a-zA-Z\s]+$/', $contactPerson)){
                $errors['contactPerson'] = 'Contact Person must only contain letters, and spaces <br />';
            }
        }

        if(array_filter($errors)){
            //echo 'errors';
        }
        else{
            $SID = mysqli_real_escape_string($conn, $_POST['SID']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);
            $contactPerson = mysqli_real_escape_string($conn, $_POST['contactPerson']);

            // create sql
            $sql = "INSERT INTO incomesources(SID, Address, ContactPerson)
                    VALUES('$SID', '$address', '$contactPerson' )";

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
        <h4 class="center">Earnings</h4>
        <form class="white" action="form_incomesources.php" method="POST">
            <label>Source ID </label>
            <input type="text" name="SID" value="<?php echo htmlspecialchars($SID)?>">
            <div class="red-text"><?php echo $errors['SID']; ?></div>
            
            <label>Address </label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($address)?>">
            <div class="red-text"><?php echo $errors['address']; ?></div>
            
            <label>Contact Person</label>
            <input type="text" name="contactPerson" value="<?php echo htmlspecialchars($contactPerson)?>">
            <div class="red-text"><?php echo $errors['contactPerson']; ?></div>
            
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>