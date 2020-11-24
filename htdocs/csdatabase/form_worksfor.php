<?php

    include('db_connect.php');  

    $EmployerID = $TID = $annualSalary = $date ='';
    $errors = array('EmployerID' => '', 'TID'=>'', 'annualSalary'=>'', 'date'=>'');
    
    if(isset($_POST['submit'])){
        if(empty($_POST['EmployerID'])){
            $errors['EmployerID'] = 'A \'Employer ID\' is required <br />';
        }
        else{
            $EmployerID = $_POST['EmployerID'];
            if(!preg_match('/^[0-9]{20}$/', $EmployerID)){ //{#} is the length it will match
                $errors['EmployerID'] = 'Employer ID must only be numbers with a length of 20 <br />';
            }
        }

        if(empty($_POST['TID'])){
            $errors['TID'] = 'A \'Personal TID Number\' is required <br />';
        }
        else{
            $TID = $_POST['TID'];
            if(!preg_match('/^[0-9]{20}$/', $TID)){ //{#} is the length it will match
                $errors['TID'] = 'TID must only be numbers with a length of 20 <br />';
            }
        }

        if(empty($_POST['annualSalary'])){
            $errors['annualSalary'] = 'An \'Annual Salary\' is required <br />';
        }
        else{
            $annualSalary = $_POST['annualSalary'];
            if(!preg_match('/^([0-9]+(\.[0-9]*){0,1})*$/', $annualSalary)){
                $errors['annualSalary'] = 'Annual Salary must contain only numbers <br />';
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

        if(array_filter($errors)){
            //echo 'errors';
        }
        else{

            $EmployerID = mysqli_real_escape_string($conn, $_POST['EmployerID']);
            $TID = mysqli_real_escape_string($conn, $_POST['TID']);
            $annualSalary = mysqli_real_escape_string($conn, $_POST['annualSalary']);
            $date = mysqli_real_escape_string($conn, $_POST['date']);



            // create sql
            $sql = "INSERT INTO worksfor(EmployerID, TID, AnnualSalary, DateJoined)
                    VALUES('$EmployerID', '$TID', '$annualSalary', '$date')";

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
        <h4 class="center">Works For</h4>
        <form class="white" action="form_worksfor.php" method="POST">
            <label>Employer ID </label>
            <input type="text" name="EmployerID" value="<?php echo htmlspecialchars($EmployerID)?>">
            <div class="red-text"><?php echo $errors['EmployerID']; ?></div>
            
            <label>TID</label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">
            <div class="red-text"><?php echo $errors['TID']; ?></div>
            
            <label>Annual Sallary</label>
            <input type="text" name="annualSalary" value="<?php echo htmlspecialchars($annualSalary)?>">
            <div class="red-text"><?php echo $errors['annualSalary']; ?></div>
            
            <label>Date Joined</label>
            <input type="text" name="date" value="<?php echo htmlspecialchars($date)?>">
            <div class="red-text"><?php echo $errors['date']; ?></div>
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>