<?php

    include('db_connect.php');  

    $TID = $firstName = $middleInitial = $lastName = $age = $sex = $DoB = $address = $city = $state = $zip = $dependents = '';
    $errors = array('TID' => '', 'firstName' => '', 'middleInitial' => '', 'lastName' => '', 'age' => '', 'sex' => '', 'DoB' => '',
                    'address' => '', 'city' => '', 'state' => '', 'zip' => '', 'dependents'  => '');
    
    if(isset($_POST['submit'])){
        if(empty($_POST['TID'])){
            $errors['TID'] = 'A \'Personal TID Number\' is required <br />';
        }
        else{
            $TID = $_POST['TID'];
            if(!preg_match('/^[0-9]{20}$/', $TID)){ //{#} is the length it will match
                $errors['TID'] = 'TID must only be numbers with a length of 20 <br />';
            }
        }
        
        if(empty($_POST['firstName'])){
            $errors['firstName'] = 'A \'First Name\' is required <br />';
        }
        else{
            $firstName = $_POST['firstName'];
            if(!preg_match('/^[a-zA-Z0-9\s]+$/', $firstName)){
                $errors['firstName'] = 'First Name must only contain letters, numbers, and spaces<br />';
            }
        }
        
        if(empty($_POST['middleInitial'])){
            $errors['middleInitial'] = 'A \'Middle Initial\' is required <br />';
        }
        else{
            $middleInitial = $_POST['middleInitial'];
            if(!preg_match('/^[a-zA-Z0-9]$/', $middleInitial)){
                $errors['middleInitial'] = 'Middle Initial must only contain letters and numbers<br />';
            }
        }
        
        if(empty($_POST['lastName'])){
            $errors['lastName'] = 'A \'Last Name\' is required <br />';
        }
        else{
            $lastName = $_POST['lastName'];
            if(!preg_match('/^[a-zA-Z0-9\s]+$/', $lastName)){
                $errors['lastName'] = 'Last Name must only contain letters, numbers, and spaces<br />';
            }
        }

        if(empty($_POST['age'])){
            $errors['age'] = '\'Age\' is required <br />';
        }
        else{
            $age = $_POST['age'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $age)){
                $errors['age'] = 'Age must contain only numbers <br />';
            }
        }
        
        if(empty($_POST['sex'])){
            $errors['sex'] = '\'Sex\' is required <br />';
        }
        else{
            $sex = $_POST['sex'];
            if(!preg_match('/^[a-zA-Z0-9\s]+$/', $sex)){
                $errors['sex'] = 'Sex must only contain letters, numbers, and spaces<br />';
            }
        }

        if(empty($_POST['DoB'])){
            $errors['DoB'] = 'A \'DoB\' is required <br />';
        }
        else{
            $DoB = $_POST['DoB'];
            if(!preg_match('/^([0-9]{4}-[0-9]{1,2}-[0-9]{1,2})$/', $DoB)){
                $errors['DoB'] = 'DoB must be in format YYYY-MM-DD <br />';
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
        
        if(empty($_POST['city'])){
            $errors['city'] = '\'City\' is required <br />';
        }
        else{
            $city = $_POST['city'];
            if(!preg_match('/^[a-zA-Z0-9\s]+$/', $city)){
                $errors['city'] = 'City must only contain letters, numbers, and spaces<br />';
            }
        }
        
        if(empty($_POST['state'])){
            $errors['state'] = '\'State\' is required <br />';
        }
        else{
            $state = $_POST['state'];
            if(!preg_match('/^[a-zA-Z0-9\s]{2}$/', $state)){
                $errors['state'] = 'state must only contain letters and must be length of 2<br />';
            }
        }

        if(empty($_POST['dependents'])){
            $errors['dependents'] = '\'Dependents\' is required <br />';
        }
        else{
            $dependents = $_POST['dependents'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $dependents)){
                $errors['dependents'] = 'Dependents must contain only numbers <br />';
            }
        }
        
        if(empty($_POST['zip'])){
            $errors['zip'] = '\'ZIP\' is required <br />';
        }
        else{
            $zip = $_POST['zip'];
            if(!preg_match('/^[a-zA-Z0-9\s]+$/', $zip)){
                $errors['zip'] = 'ZIP must only contain letters, numbers, and spaces<br />';
            }
        }

        if(array_filter($errors)){
            //echo 'errors';
        }
        else{

            $TID = mysqli_real_escape_string($conn, $_POST['TID']);
            $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
            $middleInitial = mysqli_real_escape_string($conn, $_POST['middleInitial']);
            $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
            $age = mysqli_real_escape_string($conn, $_POST['age']);
            $sex = mysqli_real_escape_string($conn, $_POST['sex']);
            $DoB = mysqli_real_escape_string($conn, $_POST['DoB']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);
            $city = mysqli_real_escape_string($conn, $_POST['city']);
            $state = mysqli_real_escape_string($conn, $_POST['state']);
            $dependents = mysqli_real_escape_string($conn, $_POST['dependents']);
            $zip = mysqli_real_escape_string($conn, $_POST['zip']);

            // create sql
            $sql = "INSERT INTO taxpayer(TID, FirstName, MiddleInitial, LastName, Age, Sex, DoB, ResAddress, ResCity, ResState, NumDependents, ResZip)
                    VALUES('$TID', '$firstName', '$middleInitial', '$lastName', '$age' , '$sex' , '$DoB' , '$address' , '$city' , '$state' , '$dependents', '$zip')";

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
        <h4 class="center">Add New User</h4>
        <form class="white" action="form_newuser.php" method="POST">
            <label>Tax Payer ID</label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">
            <div class="red-text"><?php echo $errors['TID']; ?></div>
            <label>First Name</label>
            <input type="text" name="firstName" value="<?php echo htmlspecialchars($firstName)?>">
            <div class="red-text"><?php echo $errors['firstName']; ?></div>
            <label>Middle Initial</label>
            <input type="text" name="middleInitial" value="<?php echo htmlspecialchars($middleInitial)?>">
            <div class="red-text"><?php echo $errors['middleInitial']; ?></div>
            <label>Last Name</label>
            <input type="text" name="lastName" value="<?php echo htmlspecialchars($lastName)?>">
            <div class="red-text"><?php echo $errors['lastName']; ?></div>
            <label>Age</label>
            <input type="text" name="age" value="<?php echo htmlspecialchars($age)?>">
            <div class="red-text"><?php echo $errors['age']; ?></div>
            <label>Sex</label>
            <input type="text" name="sex" value="<?php echo htmlspecialchars($sex)?>">
            <div class="red-text"><?php echo $errors['sex']; ?></div>
            <label>Date of Birth</label>
            <input type="text" name="DoB" value="<?php echo htmlspecialchars($DoB)?>">
            <div class="red-text"><?php echo $errors['DoB']; ?></div>
            <label>Address</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($address)?>">
            <div class="red-text"><?php echo $errors['address']; ?></div>
            <label>City</label>
            <input type="text" name="city" value="<?php echo htmlspecialchars($city)?>">
            <div class="red-text"><?php echo $errors['city']; ?></div>
            <label>State</label>
            <input type="text" name="state" value="<?php echo htmlspecialchars($state)?>">
            <div class="red-text"><?php echo $errors['state']; ?></div>
            <label>Zip Code</label>
            <input type="text" name="zip" value="<?php echo htmlspecialchars($zip)?>">
            <div class="red-text"><?php echo $errors['zip']; ?></div>
            <label>Number of Dependents</label>
            <input type="text" name="dependents" value="<?php echo htmlspecialchars($dependents)?>">
            <div class="red-text"><?php echo $errors['dependents']; ?></div>
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>