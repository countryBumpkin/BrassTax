<?php

    include('db_connect.php');  

    $TID = $date = $year = $description = $taxCategory = $amount = $purpose = $employerAuthorized = '';
    $errors = array('TID' => '', 'date'=>'', 'year'=>'', 'description'=>'', 'taxCategory'=>'', 'amount'=>'' ,'purpose'=>''
                    , 'employerAuthorized'=>'');
    
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

        if(empty($_POST['purpose'])){
            $errors['purpose'] = 'A \'Purpose\' is required <br />';
        }
        else{
            $purpose = $_POST['purpose'];
            if(!preg_match('/^[a-zA-Z0-9\s]+$/', $purpose)){
                $errors['purpose'] = 'Purpose must only contain letters, numbers, and spaces <br />';
            }
        }

        if(empty($_POST['employerAuthorized'])){
            $errors['employerAuthorized'] = '\'Employer Authorized\' is required <br />';
        }
        else{
            $employerAuthorized = $_POST['employerAuthorized'];
            if(!preg_match('/^[a-zA-Z]{0,4}$/', $employerAuthorized)){
                $errors['employerAuthorized'] = 'Employer Authorized must only contain letters <br />';
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
            $purpose = mysqli_real_escape_string($conn, $_POST['purpose']);
            $employerAuthorized = mysqli_real_escape_string($conn, $_POST['employerAuthorized']);

            // create sql
            $sql = "INSERT INTO employmentexpenses(TID, ExPDate, TaxYear, Description, TaxCategory, Amount, Purpose, EmployerAuthorized)
                    VALUES('$TID', '$date', '$year', '$description', '$taxCategory', '$amount', '$purpose', '$employerAuthorized' )";

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
        <h4 class="center">Employer Expenses</h4>
        <form class="white" action="form_employmentexpenses.php" method="POST">
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
            
            <label>Purpose</label>
            <input type="text" name="purpose" value="<?php echo htmlspecialchars($purpose)?>">
            <div class="red-text"><?php echo $errors['purpose']; ?></div>
            
            <label>Employer Authorized</label>
            <input type="text" name="employerAuthorized" value="<?php echo htmlspecialchars($employerAuthorized)?>">
            <div class="red-text"><?php echo $errors['employerAuthorized']; ?></div>
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>