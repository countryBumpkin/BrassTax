<?php

    include('db_connect.php');

    $TID = $depYear = $depFirst = $depLast = $SSN = $relationship = $childTaxCredit = $creditForOtherDependents = '';
    $errors = array('TID' => '', 'depYear' => '', 'depFirst' => '', 'depLast' => '', 'SSN'=>'', 'relationship'=>'',
                    'childTaxCredit' => '', 'creditForOtherDependents' => '');
    
    if(isset($_POST['submit'])){
        if(empty($_POST['TID'])){
            $errors['TID'] = 'A \'Personal TID Number\' is required <br />';
        }
        else{
            $TID = $_POST['TID'];
            if(!preg_match('/^[0-9]{20}$/', $TID)){ //{#} is the length it will match
                $errors['TID'] = 'TID must only be numbers with a length of 20 <br />';
            }
            //echo htmlspecialchars($_POST['TID']);
        }

        if(empty($_POST['depYear'])){
            $errors['depYear'] = 'A \'Dependent Year\' is required <br />';
        }
        else{
            $depYear = $_POST['depYear'];
            if(!preg_match('/^[0-9]{4}$/', $depYear)){
                $errors['depYear'] = 'Dependent year must be only numbers in format YYYY<br />';
            }
        }

        if(empty($_POST['depFirst'])){
            $errors['depFirst'] = 'A \'Dependent First Name\' is required <br />';
        }
        else{
            $depFirst = $_POST['depFirst'];
            if(!preg_match('/^[a-zA-Z]*$/', $depFirst)){
                $errors['depFirst'] = 'Dependent first name must contain only letters <br />';
            }
        }
        
        if(empty($_POST['depLast'])){
            $errors['depLast'] = 'A \'Dependent Last Name\' is required <br />';
        }
        else{
            $depLast = $_POST['depLast'];
            if(!preg_match('/^[a-zA-Z]*$/', $depLast)){
                $errors['depLast'] = 'Dependent last name must contain only letters <br />';
            }
        }

        if(empty($_POST['SSN'])){
            $errors['SSN'] = 'A \'Social Security Number\' is required <br />';
        }
        else{
            $SSN = $_POST['SSN'];
            if(!preg_match('/^[0-9]{3}-[0-9]{2}-[0-9]{4}$/', $SSN)){
                $errors['SSN'] = 'Social Security Number must be in format ###-##-#### <br />';
            }
        }

        if(empty($_POST['relationship'])){
            $errors['relationship'] = 'A \'Relationship\' is required <br />';
        }
        else{
            $relationship = $_POST['relationship'];
            if(!preg_match('/^[a-zA-Z]*$/', $relationship)){
                $errors['relationship'] = 'Relationship must contain only letters <br />';
            }
        }

        if(empty($_POST['childTaxCredit'])){
            $errors['childTaxCredit'] = 'A \'Child Tax Credit\' is required <br />';
        }
        else{
            $childTaxCredit = $_POST['childTaxCredit'];
            if(!preg_match('/^[0-9]{0,3}$/', $childTaxCredit)){
                $errors['childTaxCredit'] = 'Child Tax Credit must contain only up to 3 numbers <br />';
            }
        }

        if(empty($_POST['creditForOtherDependents'])){
            $errors['creditForOtherDependents'] = '\'Credit For Other Dependents\' is required <br />';
        }
        else{
            $creditForOtherDependents = $_POST['creditForOtherDependents'];
            if(!preg_match('/^[0-9]{0,3}$/', $creditForOtherDependents)){
                $errors['creditForOtherDependents'] = 'Credit For Other Dependents must contain only up to 3 numbers <br />';
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
            $sql = "INSERT INTO dependent(TID, depYear, depFirst, depLast, SSN, relationship, childTaxCredit, creditForOtherDependents)
                    VALUES('$TID', '$depYear', '$depFirst', '$depLast', '$SSN', '$relationship', '$childTaxCredit', '$creditForOtherDependents' )";

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
        <h4 class="center">Dependent</h4>
        <form class="white" action="form_dependent.php" method="POST">
            <label>Personal TID</label>
            <input type="text" name="TID" value="<?php echo htmlspecialchars($TID)?>">
            <div class="red-text"><?php echo $errors['TID']; ?></div>
            
            <label>Dependent Year</label>
            <input type="text" name="depYear" value="<?php echo htmlspecialchars($depYear)?>">
            <div class="red-text"><?php echo $errors['depYear']; ?></div>
            
            <label>Dependent First Name</label>
            <input type="text" name="depFirst" value="<?php echo htmlspecialchars($depFirst)?>">
            <div class="red-text"><?php echo $errors['depFirst']; ?></div>
                        
            <label>Dependent Last Name</label>
            <input type="text" name="depLast" value="<?php echo htmlspecialchars($depLast)?>">
            <div class="red-text"><?php echo $errors['depLast']; ?></div>
            
            <label>Social Security Number</label>
            <input type="text" name="SSN" value="<?php echo htmlspecialchars($SSN)?>">
            <div class="red-text"><?php echo $errors['SSN']; ?></div>
            
            <label>Relationship</label>
            <input type="text" name="relationship" value="<?php echo htmlspecialchars($relationship)?>">
            <div class="red-text"><?php echo $errors['relationship']; ?></div>
            
            <label>Child Tax Credit</label>
            <input type="text" name="childTaxCredit" value="<?php echo htmlspecialchars($childTaxCredit)?>">
            <div class="red-text"><?php echo $errors['childTaxCredit']; ?></div>
            
            <label>Credit For Other Dependents</label>
            <input type="text" name="creditForOtherDependents" value="<?php echo htmlspecialchars($creditForOtherDependents)?>">
            <div class="red-text"><?php echo $errors['creditForOtherDependents']; ?></div>
            
            <div class="center">
                <input type="submit" name="submit" value ="submit" 
                class="btn buttonColor z-depth-0">
            </div>
        </form>
    </section>

    <?php include('templates/footer.php'); ?>

</html>