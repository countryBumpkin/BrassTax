<?php

    include('db_connect.php');  

    $TID = $date = $year = $homeID = $renterID = $amount = '';
    $errors = array('TID' => '', 'date'=>'', 'year'=>'', 'homeID'=>'', 'renterID'=>'', 'amount'=>'');
    
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

        if(empty($_POST['date'])){
            $errors['date'] = 'A \'Date\' is required <br />';
        }
        else{
            $date = $_POST['date'];
            if(!preg_match('/^([0-9]{4}-[0-9]{1,2}-[0-9]{1,2})$/', $date)){
                $errors['date'] = 'Date must be in format YYYY-MM-DD <br />';
            }
        }

        if(empty($_POST['year'])){
            $errors['year'] = 'A \'Tax Year\' is required <br />';
        }
        else{
            $year = $_POST['year'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $year)){
                $errors['year'] = 'Tax Year must contain only numbers <br />';
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

        if(empty($_POST['renterID'])){
            $errors['renterID'] = 'A \'Renter ID\' is required <br />';
        }
        else{
            $renterID = $_POST['renterID'];
            if(!preg_match('/^[0-9]{20}$/', $renterID)){ //{#} is the length it will match
                $errors['renterID'] = 'Renter ID must only be numbers with a length of 20 <br />';
            }
        }

        if(empty($_POST['amount'])){
            $errors['amount'] = 'An \'Amount\' is required <br />';
        }
        else{
            $amount = $_POST['amount'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $amount)){
                $errors['amount'] = 'Amount must contain only numbers <br />';
            }
        }

        if(array_filter($errors)){
            //echo 'errors';
        }
        else{
            $TID = $date = $year = $homeID = $renterID = $amount = '';

            $TID = mysqli_real_escape_string($conn, $_POST['TID']);
            $date = mysqli_real_escape_string($conn, $_POST['date']);
            $year = mysqli_real_escape_string($conn, $_POST['year']);
            $homeID = mysqli_real_escape_string($conn, $_POST['homeID']);
            $renterID = mysqli_real_escape_string($conn, $_POST['renterID']);
            $amount = mysqli_real_escape_string($conn, $_POST['amount']);

            // create sql
            $sql = "INSERT INTO rentalearnings(TID, EarnDate, TaxYear, HomeID, RenterID, Amount)
                    VALUES('$TID', '$date', '$year', '$homeID', '$renterID', '$amount' )";

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
        <h4 class="center">Rental Earnings</h4>
        <form class="white" action="form_rentalearnings.php" method="POST">
            <label>Your Personal TID </label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">
            <div class="red-text"><?php echo $errors['TID']; ?></div>
            
            <label>Date </label>
            <input type="text" name="date" value="<?php echo htmlspecialchars($date)?>">
            <div class="red-text"><?php echo $errors['date']; ?></div>
            
            <label>Tax Year</label>
            <input type="text" name="year" value="<?php echo htmlspecialchars($year)?>">
            <div class="red-text"><?php echo $errors['year']; ?></div>
            
            <label>Home ID</label>
            <input type="text" name="homeID" value="<?php echo htmlspecialchars($homeID)?>">
            <div class="red-text"><?php echo $errors['homeID']; ?></div>
            
            <label>Renter ID</label>
            <input type="text" name="renterID" value="<?php echo htmlspecialchars($renterID)?>">
            <div class="red-text"><?php echo $errors['renterID']; ?></div>
            
            <label>Amount</label>
            <input type="text" name="amount" value="<?php echo htmlspecialchars($amount)?>">
            <div class="red-text"><?php echo $errors['amount']; ?></div>
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>