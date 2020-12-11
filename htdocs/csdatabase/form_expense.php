<?php

    include('db_connect.php');

    $TID = $date = $year = $description = $category = $amount = '';
    $errors = array('TID' => '', 'date' => '', 'year' => '', 'description' => '', 'category'=>'', 'amount'=>'');
    
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

        if(empty($_POST['description'])){
            $errors['description'] = 'A \'Description\' is required <br />';
        }
        else{
            $description = $_POST['description'];
            if(!preg_match('/^[a-zA-Z0-9\s]+$/', $description)){
                $errors['description'] = 'Description must only contain letters, numbers, and spaces <br />';
            }
        }

        if(empty($_POST['category'])){
            $errors['category'] = 'An \'Tax Category\' is required <br />';
        }
        else{
            $category = $_POST['category'];
            if(!preg_match('/^[a-zA-Z\s]+$/', $category)){
                $errors['category'] = 'Category must only contain letters, and spaces <br />';
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

        if(array_filter($errors)){
            //echo 'errors';
        }
        else{

            $TID = mysqli_real_escape_string($conn, $_POST['TID']);
            $date = mysqli_real_escape_string($conn, $_POST['date']);
            $year = mysqli_real_escape_string($conn, $_POST['year']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $category = mysqli_real_escape_string($conn, $_POST['category']);
            $amount = mysqli_real_escape_string($conn, $_POST['amount']);

            // create sql
            $sql = "INSERT INTO expenses(TID, EPDate, TaxYear, Description, TaxCategory, Amount)
                    VALUES('$TID', '$date', '$year', '$description', '$category', '$amount' )";

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
        <h4 class="center">Add Expense</h4>
        <form class="white" action="form_expense.php" method="POST">
            <label>Personal TID</label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">
            <div class="red-text"><?php echo $errors['TID']; ?></div>
            
            <label>Expense Date</label>
            <input type="text" name="date" value="<?php echo htmlspecialchars($date)?>">
            <div class="red-text"><?php echo $errors['date']; ?></div>
            
            <label>Tax Year</label>
            <input type="text" name="year" value="<?php echo htmlspecialchars($year)?>">
            <div class="red-text"><?php echo $errors['year']; ?></div>
                        
            <label>Description</label>
            <input type="text" name="description" value="<?php echo htmlspecialchars($description)?>">
            <div class="red-text"><?php echo $errors['description']; ?></div>
            
            <label>Tax Category</label>
            <input type="text" name="category" value="<?php echo htmlspecialchars($category)?>">
            <div class="red-text"><?php echo $errors['category']; ?></div>
            
            <label>Amounts </label>
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