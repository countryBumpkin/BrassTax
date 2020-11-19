<?php

    include('db_connect.php');  

    $TID = $date = $year = $description = $taxCategory = $amount = $LID = $loanNumber = $loanAmount = $remainingBalance = '';
    $errors = array('TID' => '', 'date' => '', 'year'=>'', 'description'=>'', 'taxCategory'=>'', 'amount'=>''
                    , 'LID'=>'','loanNumber'=>'', 'loanAmount' => '', 'remainingBalance'=>'');
    
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
        
        if(empty($_POST['year'])){
            $errors['year'] = 'A \'Tax Year\' is required <br />';
        }
        else{
            $year = $_POST['year'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $year)){
                $errors['year'] = 'Tax Year must contain only numbers <br />';
            }
        }

        if(empty($_POST['description'])){
            $errors['description'] = 'A \'Description\' is required <br />';
        }
        else{
            $description = $_POST['description'];
            if(!preg_match('/^[a-zA-Z0-9\s]+$/', $description)){
                $errors['description'] = 'Description must only contain letters, numbers, and spaces <br />';
            }
        }

        if(empty($_POST['taxCategory'])){
            $errors['taxCategory'] = 'An \'Tax Category\' is required <br />';
        }
        else{
            $taxCategory = $_POST['taxCategory'];
            if(!preg_match('/^[a-zA-Z\s]+$/', $taxCategory)){
                $errors['taxCategory'] = 'Category must only contain letters, and spaces <br />';
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

        if(empty($_POST['LID'])){
            $errors['LID'] = 'A \'LID\' is required <br />';
        }
        else{
            $LID = $_POST['LID'];
            if(!preg_match('/^[0-9]{20}$/', $LID)){ //{#} is the length it will match
                $errors['LID'] = 'LID must only be numbers with a length of 20 <br />';
            }
        }
        
        if(empty($_POST['loanNumber'])){
            $errors['loanNumber'] = 'A \'Loan Number\' is required <br />';
        }
        else{
            $loanNumber = $_POST['loanNumber'];
            if(!preg_match('/^[0-9]{25}$/', $loanNumber)){ //{#} is the length it will match
                $errors['loanNumber'] = 'Loan Number must only be numbers with a length of 25<br />';
            }
        }

        if(empty($_POST['loanAmount'])){
            $errors['loanAmount'] = 'An \'Loan Amount\' is required <br />';
        }
        else{
            $loanAmount = $_POST['loanAmount'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $loanAmount)){
                $errors['loanAmount'] = 'Loan Amount must contain only numbers <br />';
            }
        }

        if(empty($_POST['remainingBalance'])){
            $errors['remainingBalance'] = 'An \'Remaining Balance\' is required <br />';
        }
        else{
            $remainingBalance = $_POST['remainingBalance'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $remainingBalance)){
                $errors['remainingBalance'] = 'Remaining Balance must contain only numbers <br />';
            }
        }

        if(array_filter($errors)){
            //echo 'errors';
        }
        else{

            $TID = mysqli_real_escape_string($conn, $_POST['TID']);
            $date = mysqli_real_escape_string($conn, $_POST['date']);
            $year = mysqli_real_escape_string($conn, $_POST['year']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $taxCategory = mysqli_real_escape_string($conn, $_POST['taxCategory']);
            $amount = mysqli_real_escape_string($conn, $_POST['amount']);
            $LID = mysqli_real_escape_string($conn, $_POST['LID']);
            $loanNumber = mysqli_real_escape_string($conn, $_POST['loanNumber']);
            $loanAmount = mysqli_real_escape_string($conn, $_POST['loanAmount']);
            $remainingBalance = mysqli_real_escape_string($conn, $_POST['remainingBalance']);

            // create sql
            $sql = "INSERT INTO loanexpenses(TID, LPDate, TaxYear, Description, TaxCategory, Amount, LenderID, LoanNumber, LoanAmount, RemainingBalance)
                    VALUES('$TID', '$date', '$year', '$description', '$taxCategory', '$amount', '$LID', '$loanNumber', '$loanAmount', '$remainingBalance' )";

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
        <h4 class="center">Loan Expenses</h4>
        <form class="white" action="form_loanexpenses.php" method="POST">
            <label>Tax Payer ID</label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">
            <div class="red-text"><?php echo $errors['TID']; ?></div>
            
            <label>Date</label>
            <input type="text" name="date" value="<?php echo htmlspecialchars($date)?>">
            <div class="red-text"><?php echo $errors['date']; ?></div>

            <label>Tax Year</label>
            <input type="text" name="year" value="<?php echo htmlspecialchars($year)?>">
            <div class="red-text"><?php echo $errors['year']; ?></div>         
            
            <label>Description</label>
            <input type="text" name="description" value="<?php echo htmlspecialchars($description)?>">
            <div class="red-text"><?php echo $errors['description']; ?></div>
            
            <label>Tax Category</label>
            <input type="text" name="taxCategory" value="<?php echo htmlspecialchars($taxCategory)?>">
            <div class="red-text"><?php echo $errors['taxCategory']; ?></div>
            
            <label>Amount</label>
            <input type="text" name="amount" value="<?php echo htmlspecialchars($amount)?>">
            <div class="red-text"><?php echo $errors['amount']; ?></div>
            
            <label>Lender ID</label>
            <input type="text" name="LID" value="<?php echo htmlspecialchars($LID)?>">
            <div class="red-text"><?php echo $errors['LID']; ?></div>
            
            <label>Loan Number</label>
            <input type="text" name="loanNumber" value="<?php echo htmlspecialchars($loanNumber)?>">
            <div class="red-text"><?php echo $errors['loanNumber']; ?></div>
            
            <label>Loan Amount</label>
            <input type="text" name="loanAmount" value="<?php echo htmlspecialchars($loanAmount)?>">
            <div class="red-text"><?php echo $errors['loanAmount']; ?></div>
            
            <label>Remaining Balance</label>
            <input type="text" name="remainingBalance" value="<?php echo htmlspecialchars($remainingBalance)?>">
            <div class="red-text"><?php echo $errors['remainingBalance']; ?></div>
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>