<?php

    include('db_connect.php');

    $TID = $date = $taxYear = $amount = $taxWithheld = $EID = $retirementContributions = '';
    $errors = array('TID' => '', 'date'=>'', 'taxYear'=>'', 'amount'=>'', 'taxWithheld'=>'', 'EID'=>''
                    , 'retirementContributions'=>'');
    
    if(isset($_POST['submit'])){
        if(empty($_POST['TID'])){
            $errors['TID'] = 'A \'TID\' is required <br />';
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
        
        if(empty($_POST['taxYear'])){
            $errors['taxYear'] = 'A \'Tax Year\' is required <br />';
        }
        else{
            $taxYear = $_POST['taxYear'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $taxYear)){
                $errors['taxYear'] = 'Tax Year must contain only numbers <br />';
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
        
        if(empty($_POST['taxWithheld'])){
            $errors['taxWithheld'] = 'A \'Tax Withheld\' is required <br />';
        }
        else{
            $taxWithheld = $_POST['taxWithheld'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $taxWithheld)){
                $errors['taxWithheld'] = 'Tax Withheld must contain only numbers <br />';
            }
        }
        
        if(empty($_POST['EID'])){
            $errors['EID'] = 'A \'EID\' is required <br />';
        }
        else{
            $EID = $_POST['EID'];
            if(!preg_match('/^[0-9]{20}$/', $EID)){
                $errors['EID'] = 'EID must only be numbers with a length of 20 <br />';
            }
        }
        
        if(empty($_POST['retirementContributions'])){
            $errors['retirementContributions'] = 'A \'Retirement Contribution\' is required <br />';
        }
        else{
            $retirementContributions = $_POST['retirementContributions'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $retirementContributions)){
                $errors['retirementContributions'] = 'Retirement Contributions must contain only numbers <br />';
            }
        }

        if(array_filter($errors)){
            //echo 'errors';
        }
        else{

            $TID = mysqli_real_escape_string($conn, $_POST['TID']);
            $date = mysqli_real_escape_string($conn, $_POST['date']);
            $taxYear = mysqli_real_escape_string($conn, $_POST['taxYear']);
            $amount = mysqli_real_escape_string($conn, $_POST['amount']);
            $taxWithheld = mysqli_real_escape_string($conn, $_POST['taxWithheld']);
            $EID = mysqli_real_escape_string($conn, $_POST['EID']);
            $retirementContributions = mysqli_real_escape_string($conn, $_POST['retirementContributions']);

            // create sql
            $sql = "INSERT INTO employmentearnings(TID, EarnDate, TaxYear, Amount, TaxWithheld, EmployerID, RetirementContributions)
                    VALUES('$TID', '$date', '$taxYear', '$amount', '$taxWithheld', '$EID', '$retirementContributions' )";

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
        <h4 class="center">Employment Earnings</h4>
        <form class="white" action="form_employmentearnings.php" method="POST">
            <label>Personal TID </label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">
            <div class="red-text"><?php echo $errors['TID']; ?></div>
            <label>Earned Date </label>
            <input type="text" name="date" value="<?php echo htmlspecialchars($date)?>">
            <div class="red-text"><?php echo $errors['date']; ?></div>
            <label>Tax Year</label>
            <input type="text" name="taxYear" value="<?php echo htmlspecialchars($taxYear)?>">
            <div class="red-text"><?php echo $errors['taxYear']; ?></div>
            <label>Amount</label>
            <input type="text" name="amount" value="<?php echo htmlspecialchars($amount)?>">
            <div class="red-text"><?php echo $errors['amount']; ?></div>
            <label>Tax Withheld</label>
            <input type="text" name="taxWithheld" value="<?php echo htmlspecialchars($taxWithheld)?>">
            <div class="red-text"><?php echo $errors['taxWithheld']; ?></div>
            <label>Employer ID</label>
            <input type="text" name="EID" value="<?php echo htmlspecialchars($EID)?>">
            <div class="red-text"><?php echo $errors['EID']; ?></div>
            <label>Retirement Contributions</label>
            <input type="text" name="retirementContributions" value="<?php echo htmlspecialchars($retirementContributions)?>">
            <div class="red-text"><?php echo $errors['retirementContributions']; ?></div>
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>