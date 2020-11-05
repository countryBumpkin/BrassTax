<?php

    $TID = $name = $address = $DoB = $dependents = '';
    $errors = array('TID' => '', 'name'=>'', 'address'=>'', 'DoB'=>'', 'dependents'=>'');
    
    if(isset($_POST['submit'])){
        if(empty($_POST['TID'])){
            $errors['TID'] = 'A \'Personal TID Number\' is required <br />';
        }
        else{
            $TID = $_POST['TID'];
            if(!preg_match('/^[0-9]{5}$/', $TID)){ //{#} is the length it will match
                $errors['TID'] = 'TID must only be numbers with a length of 20 <br />';
            }
            //echo htmlspecialchars($_POST['TID']);
        }

        if(empty($_POST['name'])){
            $errors['name'] = 'An \'Full Name\' is required <br />';
        }
        else{
            $name = $_POST['name'];
            if(!preg_match('/^[a-zA-Z\s]+$/', $name)){
                $errors['name'] = 'Full Name must only contain letters, and spaces <br />';
            }
        }

        if(empty($_POST['address'])){
            $errors['address'] = 'An \'Address\' is required <br />';
        }
        else{
            $address = $_POST['address'];
            if(!preg_match('/^[a-zA-Z0-9\s]+$/', $address)){
                $errors['address'] = 'Address must only contain letters, numbers, and spaces <br />';
            }
        }
        
        if(empty($_POST['DoB'])){
            $errors['DoB'] = 'A \'Date of Birth\' is required <br />';
        }
        else{
            $DoB = $_POST['DoB'];
            if(!preg_match('/^[0-9-]+$/', $DoB)){
                $errors['DoB'] = 'Date of Birth must only contain numbers and spaces <br />';
            }
        }
        
        if(empty($_POST['dependents'])){
            $errors['dependents'] = 'A \'Number of Dependents\' is required <br />';
        }
        else{
            $dependents = $_POST['dependents'];
            if(!preg_match('/^[0-9]+$/', $dependents)){
                $errors['dependents'] = 'Dependents must only contain numbers <br />';
            }
        }

        if(!array_filter($errors)){
            header('Location: company.php');
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
            <label>Full Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name)?>">
            <div class="red-text"><?php echo $errors['name']; ?></div>
            <label>Address</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($address)?>">
            <div class="red-text"><?php echo $errors['address']; ?></div>
            <label>Date of Birth</label>
            <input type="text" name="DoB" value="<?php echo htmlspecialchars($DoB)?>">
            <div class="red-text"><?php echo $errors['DoB']; ?></div>
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