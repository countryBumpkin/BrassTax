<?php

    include('db_connect.php');

    $TID = $date = $year = $amount = $withheld = '';
    $errors = array('TID' => '', 'date'=>'', 'year'=>'', 'amount'=>'', 'withheld' =>'');
    
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
            $errors['year'] = 'A \'Year\' is required <br />';
        }
        else{
            $year = $_POST['year'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $year)){
                $errors['year'] = 'Year must contain only numbers <br />';
            }
        }

        if(empty($_POST['amount'])){
            $errors['amount'] = 'At least one \'Amount\' is required <br />';
        }
        else{
            $amount = $_POST['amount'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $amount)){
                $errors['amount'] = 'Amount must contain only numbers <br />';
            }
        }

        if(empty($_POST['withheld'])){
            $errors['withheld'] = '\'Withheld\' is required <br />';
        }
        else{
            $withheld = $_POST['withheld'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $amount)){
                $errors['withheld'] = 'Withheld must contain only numbers <br />';
            }
        }

        if(array_filter($errors)){
            //echo 'errors';
        }
        else{
            $TID = mysqli_real_escape_string($conn, $_POST['TID']);
            $date = mysqli_real_escape_string($conn, $_POST['date']);
            $year = mysqli_real_escape_string($conn, $_POST['year']);
            $amount = mysqli_real_escape_string($conn, $_POST['amount']);
            $withheld = mysqli_real_escape_string($conn, $_POST['withheld']);

            // create sql
            $sql = "INSERT INTO earnings(TID, EarnDate, TaxYear, Amount, TaxWithheld)
                    VALUES('$TID', '$date', '$year', '$amount', '$withheld')";

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
        <h4 class="center">Earnings</h4>
        <form class="white" action="form_earnings.php" method="POST">
            <label>Your Personal TID </label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">
            <div class="red-text"><?php echo $errors['TID']; ?></div>
            <label>Earn Date</label>
            <input type="text" name="date" value="<?php echo htmlspecialchars($date)?>">
            <div class="red-text"><?php echo $errors['date']; ?></div>
            <label>Tax Year</label>
            <input type="text" name="year" value="<?php echo htmlspecialchars($year)?>">
            <div class="red-text"><?php echo $errors['year']; ?></div>
            <label>Amount</label>
            <input type="text" name="amount" value="<?php echo htmlspecialchars($amount)?>">
            <div class="red-text"><?php echo $errors['amount']; ?></div>
            <label>Tax Withheld</label>
            <input type="text" name="withheld" value="<?php echo htmlspecialchars($withheld)?>">
            <div class="red-text"><?php echo $errors['withheld']; ?></div>
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>