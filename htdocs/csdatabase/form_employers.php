<?php

    include('db_connect.php');

    $SID = $name = $address = $employerZIP = $classification = $contactPerson = '';
    $errors = array('SID' => '', 'name'=>'', 'address'=>'', 'employerZIP' => '', 'classification'=>'', 'contactPerson'=>'');
    
    if(isset($_POST['submit'])){
        if(empty($_POST['SID'])){
            $errors['SID'] = 'A \'Source ID Number\' is required <br />';
        }
        else{
            $SID = $_POST['SID'];
            if(!preg_match('/^[0-9]{20}$/', $SID)){ //{#} is the length it will match
                $errors['SID'] = 'SID must only be numbers with a length of 20 <br />';
            }
        }

        if(empty($_POST['name'])){
            $errors['name'] = 'A \'Name\' is required <br />';
        }
        else{
            $name = $_POST['name'];
            if(!preg_match('/^[a-zA-Z0-9\s]+$/', $name)){
                $errors['name'] = 'Name must only contain letters, numbers, and spaces<br />';
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
        
        if(empty($_POST['employerZIP'])){
            $errors['employerZIP'] = 'A \'Employer ZIP\' is required <br />';
        }
        else{
            $employerZIP = $_POST['employerZIP'];
            if(!preg_match('/^[a-zA-Z0-9\s]+$/', $employerZIP)){
                $errors['employerZIP'] = 'Employer ZIP must only contain letters, numbers, and spaces<br />';
            }
        }
        
        if(empty($_POST['classification'])){
            $errors['classification'] = 'A \'Classification\' is required <br />';
        }
        else{
            $classification = $_POST['classification'];
            if(!preg_match('/^[a-zA-Z0-9\s]+$/', $classification)){
                $errors['classification'] = 'Classification must only contain letters, numbers, and spaces<br />';
            }
        }
        
        if(empty($_POST['contactPerson'])){
            $errors['contactPerson'] = 'A \'Contact Person\' is required <br />';
        }
        else{
            $contactPerson = $_POST['contactPerson'];
            if(!preg_match('/^[a-zA-Z0-9\s]+$/', $contactPerson)){
                $errors['contactPerson'] = 'Contact Person must only contain letters, numbers, and spaces<br />';
            }
        }

        if(array_filter($errors)){
            //echo 'errors';
        }
        else{

            $SID = mysqli_real_escape_string($conn, $_POST['SID']);
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);
            $employerZIP = mysqli_real_escape_string($conn, $_POST['employerZIP']);
            $classification = mysqli_real_escape_string($conn, $_POST['classification']);
            $contactPerson = mysqli_real_escape_string($conn, $_POST['contactPerson']);

            // create sql
            $sql = "INSERT INTO employers(SID, Name, Address, EmployerZIP, Classification, ContactPerson)
                    VALUES('$SID', '$name', '$address', '$employerZIP', '$classification', '$contactPerson' )";

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
        <h4 class="center">Employers</h4>
        <form class="white" action="form_employers.php" method="POST">
            <label>Source ID </label>
            <input type="text" name="SID" value="<?php echo htmlspecialchars($SID)?>">
            <div class="red-text"><?php echo $errors['SID']; ?></div>
            
            <label>Name </label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name)?>">
            <div class="red-text"><?php echo $errors['name']; ?></div>

            <label>Address</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($address)?>">
            <div class="red-text"><?php echo $errors['address']; ?></div>
            
            <label>Employer ZIP</label>
            <input type="text" name="employerZIP" value="<?php echo htmlspecialchars($employerZIP)?>">
            <div class="red-text"><?php echo $errors['employerZIP']; ?></div>
            
            <label>Classification</label>
            <input type="text" name="classification" value="<?php echo htmlspecialchars($classification)?>">
            <div class="red-text"><?php echo $errors['classification']; ?></div>
            
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